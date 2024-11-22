<?php

namespace App\Http\Controllers;

use App\Models\AppointmentTime;
use App\Http\Requests\StoreappointmenttimeRequest;
use App\Http\Requests\UpdateappointmenttimeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MastersAppointmentTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $masterId = Auth::guard('master')->id();

        $appointmenttimes = AppointmentTime::where('master_id', $masterId)->get();


        return view('masters.appointmenttime.index', compact('appointmenttimes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masters.appointmenttime.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreappointmenttimeRequest $request)
    {
        //驗證數據
        $validated = $request->validate([
            'service_date' => 'required|date|after_or_equal:today',
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ], [
            'end_time.after' => '結束時間必須晚於開始時間。',
        ]);

        //獲取登入masterId
        $masterId = Auth::guard('master')->id();

        // 尋找是否有重疊時段
        $overlapping = AppointmentTime::where('master_id', $masterId)
            ->where('service_date', $validated['service_date']) // 同一天的預約
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($query) use ($validated) {
                        $query->where('start_time', '<', $validated['start_time'])
                            ->where('end_time', '>', $validated['end_time']);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()->with('error', '所選時段已新增，請選擇其他時段。');
        }

        // 如果沒重疊，新增時段
        $appointmentTime = AppointmentTime::create([
            'master_id' => $masterId,
            'service_date' => $validated['service_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        //根據結果返回訊息
        if ($appointmentTime) {
            return redirect()->route('masters.appointmenttime.index')->with('success', '新增成功');
        } else {
            return back()->with('error', '新增時段時發生錯誤，請再試一次。');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(AppointmentTime $appointmenttime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppointmentTime $appointmenttime)
    {
//        $data = [
//            'appointmenttime'=> $appointmenttime,
//        ];
//
//        return view('masters.appointmenttime.edit',$data);
        return view('masters.appointmenttime.edit', compact('appointmenttime'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppointmentTime $appointmenttime)
    {
//        dd($request);
        // 驗證資料
        $validated = $request->validate([
            'service_date' => 'required|date',
            'start_time' => 'required|after_or_equal:service_date', // start_time 必須在 service_date 之後
            'end_time' => 'required|after:start_time', // end_time 必須在 start_time 之後
        ]);

        // 先取得提交的資料
        $updatedData = $request->only(['service_date', 'start_time', 'end_time']);

        // 比對每個欄位是否有變更
        $changes = [];

        foreach ($updatedData as $key => $value) {
            if ($appointmenttime->$key !== $value) {
                $changes[$key] = $value;  // 只有當資料有變動時，才會添加到 $changes 陣列
            }
        }

        // 如果有變更資料，執行更新操作
        if (!empty($changes)) {
            $appointmenttime->update($changes);
            return redirect()->route('masters.appointmenttime.index')->with('success', '時段更新成功');
        }

        // 如果沒有變更資料，可以返回提示訊息或做其他處理
        return redirect()->back()->with('error', '沒有任何資料改動');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppointmentTime $appointmenttime)
    {
        $appointmenttime->delete();

        return redirect()->route('masters.appointmenttime.index')->with('success', '刪除成功');
    }
}
