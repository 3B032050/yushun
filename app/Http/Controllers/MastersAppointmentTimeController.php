<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentConfirmation;
use App\Models\AdminServiceItem;
use App\Models\AppointmentTime;
use App\Http\Requests\StoreappointmenttimeRequest;
use App\Http\Requests\UpdateappointmenttimeRequest;
use App\Models\Master;
use App\Models\MasterServiceArea;
use App\Models\ScheduleRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

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
        $masterId = Auth::guard('master')->id();
        $serviceAreas = MasterServiceArea::where('master_id', $masterId)
            ->orderBy('admin_service_item_id')
            ->get();

        if ($serviceAreas->isEmpty()) {
            return redirect()->route('masters.service_areas.create_item')
                ->with('error', '您尚未新增任何服務地區，請先選擇您的服務地區。');
        }

        return view('masters.appointmenttime.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreappointmenttimeRequest $request)
    {
       // Log::info('Received request', ['request' => $request->all()]);
        //驗證數據
        $validated = $request->validate([
            'service_date' => 'required|date|after_or_equal:today',
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                function ($attribute, $value, $fail) use ($request) {
                    $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
                    $endTime = \Carbon\Carbon::createFromFormat('H:i', $value);

                    if ($startTime->diffInHours($endTime) < 3) {
                        $fail('開始與結束時間必須至少相隔 3 小時。');
                    }

                    if ($startTime->diffInHours($endTime) < 3) {
                        return back()->with('error', '開始與結束時間必須至少相隔 3 小時。');
//                        return back()->withErrors(['error' => '開始與結束時間必須至少相隔 3 小時。'])->withInput();
                    }
                }
            ],
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
        Log::info('Attempting to create appointment time', [
            'master_id' => $masterId,
            'service_date' => $validated['service_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time']
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
    public function edit($hash_appointmenttime)
    {
        $id = Hashids::decode($hash_appointmenttime)[0] ?? null;
        if (!$id) abort(404);

        $appointmenttime = AppointmentTime::with(['schedulerecord.service'])->findOrFail($id);

        // 服務項目清單（可依需求排序）
        $items = AdminServiceItem::orderBy('name')->get();

        return view('masters.appointmenttime.edit', compact('appointmenttime', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $hash_appointmenttime)
    {
        $decoded = Hashids::decode($hash_appointmenttime);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $appointmenttime = AppointmentTime::findOrFail($id);
        // 檢查按鈕提交的行為
        if ($request->has('action')) {
            if ($request->action === 'alter') {

                // 驗證：時間＆服務項目（服務項目可選、但若有就必須存在於表）
                $validated = $request->validate([
                    'start_time'        => ['required', 'after_or_equal:service_date'],
                    'end_time'          => ['required', 'after:start_time'],
                    'service_item_id'   => ['required'],
                ], [
                    'start_time.required' => '請選擇開始時間',
                    'start_time.after_or_equal' => '開始時間必須在預約日期之後或相同',
                    'end_time.required'   => '請選擇結束時間',
                    'end_time.after'      => '結束時間需晚於開始時間',
                    'service_item_id.exists' => '所選服務項目不存在',
                ]);

                // 只更新有變更的欄位（start_time/end_time）
                $updatedData = $request->only(['start_time', 'end_time']);
                $changes = [];
                foreach ($updatedData as $key => $value) {
                    if ($appointmenttime->$key != $value) {
                        $changes[$key] = $value;
                    }
                }
                if (!empty($changes)) {
                    $appointmenttime->update($changes);
                }

                // 服務項目變更（寫到關聯的 ScheduleRecord.service_id）
                if (!empty($validated['service_item_id'])) {
                    $sr = $appointmenttime->schedulerecord;
                    if ($sr) {
                        if ($sr->service_id != $validated['service_item_id']) {
                            $sr->service_id = $validated['service_item_id'];
                            $sr->save();
                        }
                    }
                }

                // 若有任何變更，就回成功訊息；否則給提示
                if (!empty($changes) || !empty($validated['service_item_id'])) {
                    return redirect()
                        ->route('masters.appointmenttime.index')
                        ->with('success', '時段更新成功');
                }
                return back()->with('error', '未發現任何變更');

            }
            else if ($request->action == 'accept') {
                // 設置狀態為已確認
                $appointmenttime->status = 1;
                ScheduleRecord::where('id', $request->appointment_time_id)
                    ->where('master_id', $request->master_id)
                    ->update([
                        'status' => 1, // 1 代表已預約
                    ]);
                $user = $appointmenttime->user;
                $this->sendAppointmentConfirmationEmail($appointmenttime, $request, $user);

            } elseif ($request->action == 'reject') {
                // 設置狀態為不成立
                $user = $appointmenttime->user;
                $appointmenttime->status = 3;
                $this->sendAppointmentConfirmationEmail($appointmenttime, $request, $user);
            }elseif ($request->action === 'cancel') { // 👈 新增取消
                    $appointmenttime->status = 4;
                    $appointmenttime->save();

                    if ($appointmenttime->schedulerecord) {
                        $appointmenttime->schedulerecord->status = 4;
                        $appointmenttime->schedulerecord->save();
                    }
                return redirect()
                    ->route('masters.appointmenttime.index')
                    ->with('success', '訂單已取消');
            }
            // 保存狀態更改
            $appointmenttime->save();
            return redirect()->route('masters.appointmenttime.index')->with('success', '訂單已更新');
        }
            // 如果沒有變更資料，可以返回提示訊息或做其他處理
        return redirect()->back()->with('error', '沒有選擇任何動作，請重新操作');
    }

    private function sendAppointmentConfirmationEmail($appointmentTime, $request, $user)
    {
        $master = Auth::guard('master');
        $appointmentDetails = [
            'master_name' => $master->name,
            'user_name' => $user->name,
            'service_date' => $appointmentTime->service_date,
            'status'=>$appointmentTime->status,
            'appointment_time' => $appointmentTime->start_time . ' - ' . $appointmentTime->end_time,
            'service_address'=>$appointmentTime->service_address,
        ];

        if (!empty($master->email)) {
            Mail::to($master->email)->send(new AppointmentConfirmation($appointmentDetails));
        }

        if (!empty($user->email)) {
            Mail::to($user->email)->send(new AppointmentConfirmation($appointmentDetails));
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_appointmenttime)
    {
        $decoded = Hashids::decode($hash_appointmenttime);
        $id = $decoded[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $appointmenttime = AppointmentTime::findOrFail($id);
        $appointmenttime->delete();

        return redirect()->route('masters.appointmenttime.index')->with('success', '刪除成功');
    }
    public function copy(Request $request)
    {
        $masterId = Auth::guard('master')->id();

        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // ✅ 使用表單選擇的目標月份
        $monthOffset = intval($request->input('target_month', 1));
        $targetMonth = Carbon::now()->startOfMonth()->addMonthsNoOverflow($monthOffset);

        $sourceTimes = AppointmentTime::where('master_id', $masterId)
            ->whereBetween('service_date', [$currentMonthStart, $currentMonthEnd])
            ->get();

        if ($sourceTimes->isEmpty()) {
            return redirect()->route('masters.appointmenttime.index')->with('error', '本月無可複製的時段！');
        }

        $createdCount = 0;

        foreach ($sourceTimes as $time) {
            $sourceDate = Carbon::parse($time->service_date);
            $weekday = $sourceDate->dayOfWeek; // 0-6
            $weekOfMonth = intval(($sourceDate->day - 1) / 7) + 1;

            // ✅ 根據選擇月份進行週次比對
            $nextMonthDate = $targetMonth->copy();
            $count = 0;
            $targetDate = null;

            while ($nextMonthDate->month === $targetMonth->month) {
                if ($nextMonthDate->dayOfWeek === $weekday) {
                    $count++;
                    if ($count === $weekOfMonth) {
                        $targetDate = $nextMonthDate->copy();
                        break;
                    }
                }
                $nextMonthDate->addDay();
            }

            if (!$targetDate) {
                continue; // 找不到相同週次就略過
            }

            // 檢查是否已有重複時段
            $exists = AppointmentTime::where('master_id', $masterId)
                ->where('service_date', $targetDate->toDateString())
                ->where(function ($query) use ($time) {
                    $query->whereBetween('start_time', [$time->start_time, $time->end_time])
                        ->orWhereBetween('end_time', [$time->start_time, $time->end_time])
                        ->orWhere(function ($query) use ($time) {
                            $query->where('start_time', '<', $time->start_time)
                                ->where('end_time', '>', $time->end_time);
                        });
                })->exists();

            if (!$exists) {
                AppointmentTime::create([
                    'master_id' => $masterId,
                    'service_date' => $targetDate->toDateString(),
                    'start_time' => $time->start_time,
                    'end_time' => $time->end_time,
                    'status' => '0',
                ]);
                $createdCount++;
            }
        }

        return redirect()->route('masters.appointmenttime.index')->with('success', "成功複製 {$createdCount} 筆時段到下個月相同週次與星期！");
    }
}
