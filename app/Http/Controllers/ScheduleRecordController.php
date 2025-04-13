<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use App\Models\AdminServiceArea;
use App\Models\AdminServiceItem;
use App\Models\AppointmentTime;
use App\Models\Master;
use App\Models\MasterServiceArea;
use App\Models\ScheduleRecord;
use App\Http\Requests\StoreschedulerecordRequest;
use App\Http\Requests\UpdateschedulerecordRequest;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $appointmenttimes = AppointmentTime::whereNull('user_id')
            ->where('status', 0)
            ->with('master.serviceAreas')
            ->get();

        //dd($appointmenttimes);
        $items = AdminServiceItem::all();

        $data = [
            'appointmenttimes' => $appointmenttimes,
            'items' => $items,
        ];

        return view('users.schedule.create', $data);

    }



//    public function getServicePrice(Request $request)
//    {
//        $serviceId = $request->query('service_id');
//        $service = AdminServiceItem::find($serviceId);
//
//        if (!$service) {
//            return response()->json(['status' => 'error', 'message' => '找不到服務項目'], 404);
//        }
//
//        return response()->json(['status' => 'success', 'price' => $service->price]);
//    }

    public function getServicePrice(Request $request)
    {
        $serviceId = $request->query('service_id');
        $address = $request->query('address', ''); // 取得使用者輸入的地址
        $service = AdminServiceItem::find($serviceId);

        if (!$service) {
            return response()->json(['status' => 'error', 'message' => '找不到服務項目'], 404);
        }

        if (empty($address)) {
            return response()->json(['status' => 'error', 'message' => '請提供服務地點']);
        }

        // 取得地址前六個字，並進行格式轉換 (台 → 臺)
        $areaKeyword = mb_substr($address, 0, 6, "UTF-8");
        $areaKeyword = str_replace(['台', '臺'], '臺', $areaKeyword);

        // 查詢該地址是否在 AdminServiceArea
        $serviceArea = AdminServiceArea::where(DB::raw("CONCAT(TRIM(major_area), TRIM(minor_area))"), 'LIKE', $areaKeyword . '%')->first();

        if (!$serviceArea) {
            return response()->json([
                'status' => 'error',
                'message' => '找不到該地址的服務區域',
                'areaKeyword' => $areaKeyword
            ]);
        }

        $price = $service->price;

        // 如果該區域是蛋黃區 (status == 1)，則加價 30
        if ($serviceArea->status == 1) {
            $price += 30;
        }

//        session()->put("service_price_$serviceId", $price);

        return response()->json([
            'status' => 'success',
            'price' => $price,
//            'areaKeyword' => $areaKeyword,
//            'isPremium' => $serviceArea->status == 1 ? true : false
        ]);
    }

    public function available_masters(Request $request)
    {
        $date = $request->input('date', '');
        $serviceId = $request->input('service_id', '');
        $address = $request->input('address', '');
        if (empty($date)) {
            return response()->json(['status' => 'error', 'message' => '請提供日期']);
        }

        if (empty($serviceId)) {
            return response()->json(['status' => 'error', 'message' => '請選擇服務項目']);
        }

        if (empty($address)) {
            return response()->json(['status' => 'error', 'message' => '請提供服務地點']);
        }

        // 取得地址前六個字（可根據實際情況調整）

        $areaKeyword = mb_substr($address, 0, 6, "UTF-8");
        // 替換 '台' 為 '臺'
        $areaKeyword = str_replace(['台', '臺'], '臺', $areaKeyword);
        Log::info("Address: $address, Area Keyword: $areaKeyword");  // 將查詢的關鍵字輸出到日誌中

        $serviceArea = AdminServiceArea::where(DB::raw("CONCAT(TRIM(major_area), TRIM(minor_area))"), 'LIKE', $areaKeyword . '%')->first();

        if (!$serviceArea) {
            return response()->json(['status' => 'error', 'message' => '找不到該地址的服務區域', 'areaKeyword' => $areaKeyword]);
        }
        // 查詢該日期的所有預約時段並關聯師傅及服務區域
        $appointmentTimes = AppointmentTime::with('master')
            ->where('service_date', $date)
            ->whereHas('master.serviceAreas', function ($query) use ($serviceArea, $serviceId) {
                $query->where('admin_service_area_id', $serviceArea->id)
                    ->where('admin_service_item_id', $serviceId);
            })
            ->get();
        //dd($appointmentTimes->toArray());
        // 如果沒有可用的師傅
        if ($appointmentTimes->isEmpty()) {
            return response()->json(['message' => '當日無可預約師傅']);
        }

        // 過濾並去重，確保每個師傅只出現一次
        $masters = $appointmentTimes->pluck('master')->unique('id')->map(function ($master) {
            return [
                'id' => $master->id,
                'name' => $master->name,
            ];
        })->values();
        // 基於 'id' 去重並重新索引

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
        $appointmentTimes = AppointmentTime::whereNull('user_id')->where('service_date', $date)
            ->where('master_id', $masterId)
            ->where('status', 0)
            ->get();

        if ($appointmentTimes->isEmpty()) {
            Log::info('該師傅當日無可預約時段', ['date' => $date, 'master_id' => $masterId]);
            return response()->json(['message' => '該師傅當日無可預約時段'], 404);
        }

        $times = $appointmentTimes->map(function ($appointmentTime) {
            return [
                'id' => $appointmentTime->id,
                'start_time' => $appointmentTime->start_time,
                'end_time' => $appointmentTime->end_time,
            ];
        })->values(); // 使用 values() 確保是重新索引的陣列
        Log::info('Available appointment times:', ['times' => $times]);
        //dd($times);
        return response()->json($times);
    }

    public function getTotalPrice(Request $request)
    {
        $serviceId = $request->query('service_id');
        $appointmentTimeId = $request->query('appointment_time');
        $time = AppointmentTime::where('id', $appointmentTimeId)->first();
        $totalAmount = 0;
        $AdminServiceItem =AdminServiceItem::where('id', $serviceId)->first();
        // 基本價格
        $basePrice = $AdminServiceItem->price;
        $is_recurring =$request->query('is_recurring');
        $address=$request->query('address');
        $areaKeyword = mb_substr($address, 0, 6, "UTF-8");
        // 替換 '台' 為 '臺'
        $areaKeyword = str_replace(['台', '臺'], '臺', $areaKeyword);
        Log::info("Address: $address, Area Keyword: $areaKeyword");  // 將查詢的關鍵字輸出到日誌中

        $serviceArea = AdminServiceArea::where(DB::raw("CONCAT(TRIM(major_area), TRIM(minor_area))"), 'LIKE', $areaKeyword . '%')->first();
        //計算選擇時段的時數
        if ($time) {
           // list($start, $end) = explode('-', $time);
            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', trim($time->start_time));
            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', trim($time->end_time));

            // 計算總分鐘數
            $totalMinutes = $endTime->diffInMinutes($startTime); // 計算分鐘差
            // 計算總時數（可以取小數，避免使用 diffInHours）
            $totalHours = $totalMinutes / 60;
        }
        // 額外費用
        $extraFee = 0;
        if ($totalHours < 4) {
            $extraFee += 50;
        }
        if ($is_recurring == false) {
            $extraFee += 50;
        }
        if (!empty($serviceArea) && isset($serviceArea->status) && $serviceArea->status == 1)
        {
            $extraFee += 30;
        }
        // 計算總價
            $totalAmount = $totalHours * ($basePrice + $extraFee);
        return response()->json([
            'status' => 'success',
            'price' => $totalAmount,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreschedulerecordRequest $request)
    {
        // 檢查該時段是否已被預約
        $appointmentTime = AppointmentTime::find($request->appointment_time_id);

        if (!$appointmentTime) {
            return redirect()->back()->with('error', '預約時段不存在！');
        }

        $user = Auth::user();
        if ($request->input('address_option') === 'profile') {
            // 使用預設的個人地址
            $address = $user->address;
        } else {
            // 使用手動輸入的地址
            $address = $request->input('custom_address');
        }

       // dd($address);
        // 檢查當天的時段是否已被預約
        $isAlreadyBooked = ScheduleRecord::where('master_id', $request->master_id)
            ->where(function ($query) {
                $query->whereNotNull('user_id')  // user_id 有值
                ->orWhere('status', 1);   // 或者 status 為 1
            })
            ->where('service_date', $request->service_date)
            ->where('appointment_time_id', $request->appointment_time_id)

            ->exists();

        if ($isAlreadyBooked) {
            return redirect()->back()->with('error', '該時段已被預約，請選擇其他時段');
        }
        ScheduleRecord::create([
            'master_id' => $request->master_id,
            'user_id' => $user->id,
            'service_id' => $request->service_id,
            'service_address'=>$address,
            'service_date' => $request->service_date,
            'price' => $request->total_price,
            'appointment_time_id' => $request->appointment_time_id,
            'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,
            'status' => 1 // 已確認
        ]);

        // **更新該時段狀態為「已預約」**
        AppointmentTime::where('id', $request->appointment_time_id)
            ->where('master_id', $request->master_id)
            ->update([
                'user_id' => $user->id,
                'service_address'=>$address,
                'status' => 1, // 1 代表已預約
            ]);
        // **處理定期預約**
        if ($request->boolean('is_recurring') && $request->recurring_times >= 1) {
            // 根據選擇的次數和間隔天數來新增排程
            $intervalWeeks = (int) $request->recurring_interval; // 每隔幾週預約一次

            // 預設新增定期預約排程
            for ($i = 1; $i <= $request->recurring_times; $i++) {
                $futureDate = Carbon::parse($request->service_date)->addWeeks($i * $intervalWeeks);

                // 檢查該未來日期是否已有相同的預約，且該時段可預約（status = 0）
                $existingTime = AppointmentTime::where('master_id', $request->master_id)
                    ->whereNull('user_id') //沒有客戶預約
                    ->whereRaw('status = 0')  // 該時段為可預約
                    ->where('service_date', $futureDate->toDateString())
                    ->where('start_time', $appointmentTime->start_time)
                    ->where('end_time', $appointmentTime->end_time)

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
                    ->whereNull('user_id')
                    ->whereRaw('status = 0')
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
                        'price' => $request->total_price,
                        'service_address'=>$address,
                        'appointment_time_id' => $newAppointment->id, // 使用 `appointment_time_id`
                        'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time, // 使用 start_time 和 end_time
                        'status' => 0, // 0 代表待確認

                    ]);
                    AppointmentTime::where('id',  $newAppointment->id)
                        ->where('master_id', $request->master_id)
                        ->update([
                            'user_id' => $user->id,
                            'status' => 0, // 0 代確認
                            'service_address'=>$address,
                        ]);
                }
            }
        }
        elseif ($request->boolean('is_recurring') && $request->recurring_times == 0)
        {
            return redirect()->back()->with('error', '定期客戶預約次數至少為1');
        }
            // **建立當天的排程紀錄**


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
            'master_name' => $master->name ?? '未知師傅',
            'user_name' => $user->name ?? '未知用戶',
            'service_date' => $request->service_date ?? '未指定日期',
            'appointment_time' => ($appointmentTime->start_time ?? '') . ' - ' . ($appointmentTime->end_time ?? ''),
            'service_address' => $request->address ?? '未提供地址',
        ];
        Log::info('Appointment Details:', $appointmentDetails);
        if (!empty($master->email)) {
            Mail::to($master->email)->queue(new AppointmentConfirmation($appointmentDetails));
        }

        if (!empty($user->email)) {
            Mail::to($user->email)->queue(new AppointmentConfirmation($appointmentDetails));
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
    public function testMail()
    {
        $appointmentDetails = [
            'master_name' => 'master',
            'user_name' => 'admin',
            'service_date' => '2025-03-21',
            'appointment_time' => '16:54:00 - 19:54:00',
            'service_address' => '桃園市桃園區',
        ];

        // 測試發送郵件到你自己的電子郵件
        Mail::to('your_email@example.com')->send(new AppointmentConfirmation($appointmentDetails));

        // 你也可以加上一個訊息來確認郵件是否發送
        return response()->json(['message' => '郵件已發送']);
    }

    public function copy(Request $request)
    {
        $user = Auth::user();

        // 取得目前日期，抓出本月
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 找出本月這位客戶所有已預約的排程
        $scheduleRecords = ScheduleRecord::where('user_id', $user->id)
            ->whereBetween('service_date', [$startOfMonth, $endOfMonth])
            ->get();

        if ($scheduleRecords->isEmpty()) {
            return redirect()->back()->with('error', '本月無可複製的預約紀錄');
        }

        foreach ($scheduleRecords as $record) {
            $originalDate = Carbon::parse($record->service_date);
            $weekOfMonth = (int) floor(($originalDate->day - 1) / 7); // 第幾個星期幾（0-based）

            // 下個月的同一個星期幾
            $monthOffset = intval($request->input('target_month', 1));
            $targetMonth = $originalDate->copy()->addMonth($monthOffset);
            $firstDayOfMonth = $targetMonth->copy()->startOfMonth();

            $targetWeekday = $originalDate->dayOfWeek;

            // 找下個月第 N 個相同星期幾
            $count = 0;
            $targetDate = null;
            for ($day = 1; $day <= $targetMonth->daysInMonth; $day++) {
                $current = $targetMonth->copy()->day($day);
                if ($current->dayOfWeek == $targetWeekday) {
                    if ($count == $weekOfMonth) {
                        $targetDate = $current;
                        break;
                    }
                    $count++;
                }
            }

            if (!$targetDate) {
                continue; // 如果下個月沒有這個星期幾（例如本月有五個週三、下個月只有四個）
            }

            // 接下來與前面邏輯相同：新增 AppointmentTime、ScheduleRecord，並避免重複
            $appointmentTime = AppointmentTime::firstOrCreate([
                'master_id' => $record->master_id,
                'service_date' => $targetDate->toDateString(),
                'start_time' => explode(' - ', $record->appointment_time)[0],
                'end_time' => explode(' - ', $record->appointment_time)[1],
            ], [
                'status' => 0,
                'user_id' => $user->id,
                'service_address' => $record->service_address,
            ]);

            ScheduleRecord::firstOrCreate([
                'user_id' => $user->id,
                'service_date' => $targetDate->toDateString(),
                'appointment_time_id' => $appointmentTime->id,
            ], [
                'master_id' => $record->master_id,
                'service_id' => $record->service_id,
                'price' => $record->price,
                'service_address' => $record->service_address,
                'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,
                'status' => 0,
            ]);
        }

        return redirect()->route('users.schedule.index')->with('success', '本月預約已成功複製到下個月（待確認）');
    }

}
