<?php

namespace App\Http\Controllers;

use App\Models\AppointmentTime;
use App\Models\MasterServiceArea;
use App\Models\ScheduleRecord;
use App\Http\Requests\StoreschedulerecordRequest;
use App\Http\Requests\UpdateschedulerecordRequest;
use Illuminate\Support\Facades\Auth;

class ScheduleRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { $userId = Auth::guard('user')->id();

        $schedules = ScheduleRecord::where('user_id', $userId)->get();


        return view('users.schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterServiceAreas = MasterServiceArea::with('adminarea')->get();
        $appointmenttimes = AppointmentTime::all();
        return view('users.schedule.create', compact('appointmenttimes'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreschedulerecordRequest $request)
    {
        $userId = Auth::guard('user')->id();
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
