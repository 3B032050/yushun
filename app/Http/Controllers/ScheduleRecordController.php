<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use App\Models\AdminServiceItem;
use App\Models\AppointmentTime;
use App\Models\Master;
use App\Models\MasterServiceArea;
use App\Models\ScheduleRecord;
use App\Http\Requests\StoreschedulerecordRequest;
use App\Http\Requests\UpdateschedulerecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ScheduleRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // 檢查用戶是否已登入
        $user = Auth::user();

        if (!$user) {
            // 如果未登入，重定向到登入頁面或顯示錯誤訊息
            return redirect()->route('login')->with('error', '請先登入以檢視預約時段。');
        }

        // 查詢該用戶的預約時段
        $schedules = ScheduleRecord::with('appointmenttime')->where('user_id', $user->id)->get();
        //dd($schedules);

        // 檢查是否有資料
        if ($schedules->isEmpty()) {
            // 可選：返回一個視圖，顯示無預約記錄的訊息
            session()->flash('info', '目前尚無任何預約時段。');
        }
        // 傳遞資料到視圖
        return view('users.schedule.index', compact('schedules'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $appointmenttimes = AppointmentTime::with('master.serviceAreas')->get();
        //dd($appointmenttimes);
        $items = AdminServiceItem::all();

        $data = ['appointmenttimes' => $appointmenttimes,
            'items' => $items];

        return view('users.schedule.create', $data);

    }

    public function getServicePrice(Request $request)
    {
        $serviceId = $request->query('service_id');
        $service = AdminServiceItem::find($serviceId);

        if (!$service) {
            return response()->json(['status' => 'error', 'message' => '找不到服務項目'], 404);
        }

        return response()->json(['status' => 'success', 'price' => $service->price]);
    }

    public function available_masters(Request $request)
    {
        $date = $request->query('date');
        $serviceId = $request->query('service_id');

        if (!$date) {
            return response()->json(['status' => 'error', 'message' => '請提供日期']);
        }

        if (!$serviceId) {
            return response()->json(['status' => 'error', 'message' => '請選擇服務項目']);
        }

        // 查詢該日期的所有預約時段並關聯師傅及服務區域
        $appointmentTimes = AppointmentTime::with('master')
            ->where('service_date', $date)
            ->whereHas('master.serviceAreas', function ($query) use ($serviceId) {
                $query->where('admin_service_item_id', $serviceId);
            })
            ->get();

        // 如果沒有可用的師傅
        if ($appointmentTimes->isEmpty()) {
            return response()->json(['status' => 'empty', 'message' => '該師傅當日無可預約時段']);
        }

        // 過濾並去重，確保每個師傅只出現一次
        $masters = $appointmentTimes->map(function ($appointmentTime) {
            return [
                'id' => $appointmentTime->master->id,
                'name' => $appointmentTime->master->name,
            ];
        })->unique('id')->values(); // 基於 'id' 去重並重新索引

        return response()->json(['status' => 'success', 'data' => $masters]);
    }
    public function available_times(Request $request)
    {
        $date = $request->query('date');
        $masterId = $request->query('master_id');

        if (!$date || !$masterId) {
            return response()->json(['message' => '缺少日期或師傅資訊'], 400);
        }

        // 查詢該師傅於該日期的所有預約時段
        $appointmentTimes = AppointmentTime::where('service_date', $date)
            ->where('master_id', $masterId)
            ->get();

        if ($appointmentTimes->isEmpty()) {
            return response()->json(['message' => '該師傅當日無可預約時段'], 404);
        }

        $times = $appointmentTimes->map(function ($appointmentTime) {
            return [
                'id' => $appointmentTime->id,
                'start_time' => $appointmentTime->start_time,
                'end_time' => $appointmentTime->end_time,
            ];
        });

        return response()->json($times);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreschedulerecordRequest $request)
    {
        $isAlreadyBooked = ScheduleRecord::where('master_id', $request->master_id)
            ->where('service_date', $request->service_date)
            ->where('appointment_time_id', $request->appointment_time_id)
            ->where('status', 1) // 只檢查已確認的排程
            ->exists();

        if ($isAlreadyBooked) {
            return redirect()->back()->with('error', '該時段已被預約，請選擇其他時段');
        }
        // **處理定期預約**
        if ($request->boolean('is_recurring') && $request->recurring_interval > 0) {
            $intervalDays = (int) $request->recurring_interval; // 每隔幾天預約一次

            for ($i = 1; $i <= 5; $i++) { // 預設新增 5 次排程
                $futureDate = Carbon::parse($request->service_date)->addDays($intervalDays * $i);

                // 檢查該未來日期是否已有相同的預約，且該時段可預約（status = 0）
                $existingTime = AppointmentTime::where('master_id', $request->master_id)
                    ->where('service_date', $futureDate->toDateString())
                    ->where('start_time', $appointmentTime->start_time)
                    ->where('end_time', $appointmentTime->end_time)
                    ->where('status', 0) // 該時段為可預約
                    ->first(); // 如果找到該時段則返回

                // 如果沒有找到該時段，則新增該時段
                if (!$existingTime) {
                    // **新增 `AppointmentTime` 可預約時段（狀態：待確認）**
                    $newAppointment = AppointmentTime::create([
                        'master_id' => $request->master_id,
                        'service_date' => $futureDate->toDateString(),
                        'start_time' => $appointmentTime->start_time,
                        'end_time' => $appointmentTime->end_time,
                        'status' => 0, // 0 代表可預約
                    ]);
                } else {
                    // 如果該時段已經存在，只需用 `existingTime` 即可
                    $newAppointment = $existingTime;
                }

                // 檢查 `ScheduleRecord` 是否已存在該排程，避免重複
                $existingSchedule = ScheduleRecord::where('master_id', $request->master_id)
                    ->where('service_date', $futureDate->toDateString())
                    ->where('appointment_time_id', $newAppointment->id) // 使用新的或已存在的 `appointment_time_id`
                    ->exists();

                if (!$existingSchedule)
                {
                    // **新增 `ScheduleRecord` 排程（狀態：待確認）**
                    ScheduleRecord::create([
                        'master_id' => $request->master_id,
                        'user_id' => $user->id,
                        'service_id' => $request->service_id,
                        'service_date' => $futureDate->toDateString(),
                        'appointment_time_id' => $newAppointment->id, // 使用 `appointment_time_id`
                        'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time, // 使用 start_time 和 end_time
                        'status' => 0, // 0 代表待確認

                    ]);
                    AppointmentTime::where('id',  $newAppointment->id)
                        ->where('master_id', $request->master_id)
                        ->update([
                            'user_id' => $user->id,
                            'status' => 0, // 0 代確認
                        ]);
                }
            }
        }
        else
        {
            // **建立當天的排程紀錄**
            ScheduleRecord::create([
                'master_id' => $request->master_id,
                'user_id' => $user->id,
                'service_id' => $request->service_id,
                'service_date' => $request->service_date,
                'appointment_time_id' => $request->appointment_time_id,
                'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,
                'status' => 1 // 已確認
            ]);

            // **更新該時段狀態為「已預約」**
            AppointmentTime::where('id', $request->appointment_time_id)
                ->where('master_id', $request->master_id)
                ->update([
                    'user_id' => $user->id,
                    'status' => 1, // 1 代表已預約
                ]);
        }

        // 發送郵件
        $this->sendAppointmentConfirmationEmail($appointmentTime, $request, $user);

        return redirect()->route('users.schedule.index')->with('success', '預約成功');
    }

    /**
     * 發送預約確認郵件
     */
    private function sendAppointmentConfirmationEmail($appointmentTime, $request, $user)
    {
        $master = Master::find($request->master_id);
        $appointmentDetails = [
            'master_name' => $master->name,
            'user_name' => $user->name,
            'service_date' => $request->service_date,
            'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,
        ];

        if (!empty($master->email)) {
            Mail::to($master->email)->send(new AppointmentConfirmation($appointmentDetails));
        }

        if (!empty($user->email)) {
            Mail::to($user->email)->send(new AppointmentConfirmation($appointmentDetails));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduleRecord $schedulerecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleRecord $schedulerecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateschedulerecordRequest $request, ScheduleRecord $schedulerecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleRecord $schedulerecord)
    {
        //
    }
}
