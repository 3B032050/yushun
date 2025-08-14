<?php

namespace App\Http\Controllers;

use App\Models\AppointmentTime;
use App\Models\BorrowingRecord;
use App\Models\Equipment;
use App\Models\ScheduleDetail;
use App\Http\Requests\StorescheduledetailRequest;
use App\Http\Requests\UpdatescheduledetailRequest;
use App\Models\ScheduleRecord;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class ScheduleDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($hash_appointmenttime)
    {
        $decoded = Hashids::decode($hash_appointmenttime);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $appointmenttime = AppointmentTime::findOrFail($id);
        return view('masters.schedule_details.create', compact('appointmenttime'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorescheduledetailRequest $request,$hash_appointmenttime)
    {
        // 驗證圖片
        $request->validate([
            'before_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'after_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $decoded = Hashids::decode($hash_appointmenttime);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $appointmenttime = AppointmentTime::findOrFail($id);
        $scheduleRecord = $appointmenttime->scheduleRecord;
        $scheduleDetail = new ScheduleDetail();
        $scheduleDetail->schedule_record_id = $scheduleRecord->id;

        // 儲存清潔前照片
        if ($request->hasFile('before_photo')) {
            $beforeImage = $request->file('before_photo');
            $beforeImageName = time() . '_before.' . $beforeImage->getClientOriginalExtension();

            // 存入 public 路徑
            Storage::disk('schedule_photos')->put($beforeImageName, file_get_contents($beforeImage));
            $scheduleDetail->before_photo = $beforeImageName;
        }

        // 儲存清潔後照片
        if ($request->hasFile('after_photo')) {
            $afterImage = $request->file('after_photo');
            $afterImageName = time() . '_after.' . $afterImage->getClientOriginalExtension();

            Storage::disk('schedule_photos')->put($afterImageName, file_get_contents($afterImage));
            $scheduleDetail->after_photo = $afterImageName;
        }

        // 更新狀態
        $appointmenttime->update(['status' => 2]);
        $scheduleRecord->update(['status' => 2]);

        // 歸還設備數量
        $borrowingRecords = BorrowingRecord::where('appointment_time_id', $appointmenttime->id)->get();
        foreach ($borrowingRecords as $record) {
            if ($record->status == 0) {
                $equipment = Equipment::find($record->equipment_id);
                if ($equipment) {
                    $equipment->quantity += $record->quantity;
                    $equipment->save();
                }
                $record->update(['status' => 1]);
            }
        }
// 解析工時
        $start = \Carbon\Carbon::createFromFormat('H:i:s', $appointmenttime->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i:s', $appointmenttime->end_time);
        $hours = $end->diffInMinutes($start) / 60; // 換算成小時（可能有小數點）

// 更新師傅總工時
        $master = $scheduleRecord->master;
        if ($master && is_numeric($hours)) {
            $master->total_hours += $hours;
            $master->save();
        }

        $scheduleDetail->save();

        return redirect()->route('masters.appointmenttime.index')->with('success', '訂單已成功完成');
    }


    public function review(UpdatescheduledetailRequest $request)
    {
        $scheduleDetail = ScheduleDetail::where('schedule_record_id', $request->schedule_id)->first();

        if (!$scheduleDetail) {
            return response()->json(['success' => false, 'message' => '未找到對應的預約記錄'], 404);
        }

        $scheduleDetail->score = $request->score;
        $scheduleDetail->comment = $request->comment;
        $scheduleDetail->save();

        return redirect()->route('masters.appointmenttime.index')->with('success', '評論提交成功');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduleDetail $scheduledetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleDetail $scheduledetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatescheduledetailRequest $request, ScheduleDetail $scheduledetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleDetail $scheduledetail)
    {
        //
    }
}
