<?php

namespace App\Http\Controllers;

use App\Models\AppointmentTime;
use App\Models\MasterServiceArea;
use App\Models\ScheduleRecord;
use App\Http\Requests\StoreschedulerecordRequest;
use App\Http\Requests\UpdateschedulerecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { $userId = Auth::user();

        $schedules = ScheduleRecord::where('user_id', $userId)->get();


        return view('users.schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $appointmenttimes = AppointmentTime::with('master.serviceAreas')->get();

        //dd($masters);
        //dd($appointmenttimes);
        return view('users.schedule.create', compact('appointmenttimes'));

    }
//    public function (Request $request)
//    {
//        $date = $request->query('date');
//        $masterId = $request->query('master_id');
//        $serviceAreaId = $request->query('service_area');
//
//        // 確保傳入的參數存在
//        if (!$date || !$masterId || !$serviceAreaId) {
//            return response()->json(['message' => '請提供日期、師傅與服務地區'], 400);
//        }
//        //dd($date);
//        // 查找該日期、師傅和服務區域的可用時段
//        $appointmentTimes = AppointmentTime::with('master', 'serviceArea')
//            ->where('service_date', $date)
//            ->where('master_id', $masterId)
//            ->where('service_area_id', $serviceAreaId)
//            ->get();
//
//        if ($appointmentTimes->isEmpty()) {
//            return response()->json(['message' => '該日期沒有可用的時段'], 404);
//        }
//
//        // 返回可預約的時段
//        $times = $appointmentTimes->map(function ($appointmentTime) {
//            return [
//                'id' => $appointmentTime->id,
//                'start_time' => $appointmentTime->start_time,
//                'end_time' => $appointmentTime->end_time,
//            ];
//        });
//
//        return response()->json($times);
//    }
    public function available_masters(Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['message' => '請提供日期'], 400);
        }

        $appointmentTimes = AppointmentTime::with('master')
            ->where('service_date', $date)
            ->get();

        if ($appointmentTimes->isEmpty()) {
            return response()->json(['message' => '該日期沒有可用的師傅'], 404);
        }

        $masters = $appointmentTimes->map(function ($appointmentTime) {
            return [
                'id' => $appointmentTime->master->id,
                'name' => $appointmentTime->master->name,
            ];
        })->unique();

        return response()->json($masters);
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
        $userId = Auth::user();
        // 驗證後的資料
        $validatedData = $request->validated();

        // 從 appointment 資料表中抓取相關資料
        $appointment = AppointmentTime::findOrFail($validatedData['appointment_id']);

        // 創建排程記錄
        $scheduleRecord = new ScheduleRecord();
        $scheduleRecord->master_id = $appointment->master_id; // 從 appointment 中取得 master_id
        $scheduleRecord->user_id = $userId;     // 從 appointment 中取得 user_id
        $scheduleRecord->appointment_time_id = $appointment->appointment_time_id; // 從 appointment 中取得 appointment_time_id
        $scheduleRecord->price = $validatedData['price'];
        $scheduleRecord->time_period = $validatedData['time_period'] ?? null;
        $scheduleRecord->payment_date = $validatedData['payment_date'] ?? null;
        $scheduleRecord->service_date = $validatedData['service_date'] ?? null;
        $scheduleRecord->is_recurring = $validatedData['is_recurring'] ?? false;

        // 儲存資料
        $scheduleRecord->save();

        // 回應或重導
        return redirect()->route('schedules.index')
            ->with('success', '排程記錄已成功新增！');
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
