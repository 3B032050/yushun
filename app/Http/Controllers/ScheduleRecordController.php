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

        // 檢查該時段是否已被預約
        $isAlreadyBooked = ScheduleRecord::where('master_id', $request->master_id)
            ->where('service_date', $request->service_date)
            ->where('appointment_time_id', $request->appointment_time_id)
            ->exists();

        if ($isAlreadyBooked) {
            return redirect()->back()->with('error', '該時段已被預約，請選擇其他時段');
        }

        $user = Auth::user();
        $appointmentTime = AppointmentTime::find($request->appointment_time_id);
        ScheduleRecord::create([
            'master_id' => $request->master_id,
            'user_id' => $user->id,
            'service_id' => $request->service_id,
            'service_date' => $request->service_date,
            'appointment_time_id' => $request->appointment_time_id,
            'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,
            'status' => 0
        ]);
        AppointmentTime::where('id', $request->appointment_time_id)
            ->where('master_id', $request->master_id)
            ->update([
                'user_id' => $user->id,
                'status' => '1',
            ]);


        $master = Master::find($request->master_id);

        // 構建郵件內容
        $appointmentDetails = [
            'master_name' => $master->name,
            'user_name' => $user->name,
            'service_date' => $request->service_date,
            'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,

        ];

        // 發送郵件給師傅
        Mail::to($master->email)->send(new AppointmentConfirmation($appointmentDetails));

        // 發送郵件給客戶
        Mail::to($user->email)->send(new AppointmentConfirmation($appointmentDetails));
        return redirect()->route('users.schedule.index')->with('success', '預約成功');
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
