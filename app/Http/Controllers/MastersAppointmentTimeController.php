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

use Illuminate\Validation\ValidationException;
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
        try {
            $masterId = Auth::guard('master')->id();
            $serviceAreas = MasterServiceArea::where('master_id', $masterId)
                ->orderBy('admin_service_item_id')
                ->get();

            if ($serviceAreas->isEmpty()) {
                return redirect()->route('masters.service_areas.create_item')
                    ->with('error', '您尚未新增任何服務地區，請先選擇您的服務地區。');
            }

            return view('masters.appointmenttime.create');

        } catch (\Exception $e) {
            // 捕捉所有其他系統錯誤
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreappointmenttimeRequest $request)
    {
        try {
            // 驗證數據
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
                    }
                ],
            ], [
                'end_time.after' => '結束時間必須晚於開始時間。',
            ]);

            // 獲取登入 masterId
            $masterId = Auth::guard('master')->id();

            // 尋找是否有重疊時段
            $overlapping = AppointmentTime::where('master_id', $masterId)
                ->where('service_date', $validated['service_date'])
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

            // 新增時段
            $appointmentTime = AppointmentTime::create([
                'master_id' => $masterId,
                'service_date' => $validated['service_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
            ]);

            if ($appointmentTime) {
                return redirect()->route('masters.appointmenttime.index')->with('success', '新增成功');
            } else {
                return back()->with('error', '新增時段時發生錯誤，請再試一次。');
            }

        } catch (ValidationException $e) {
            // 驗證錯誤
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            // 其他系統錯誤

            return redirect()->back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
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

        try {
            $id = Hashids::decode($hash_appointmenttime)[0] ?? null;

            if (!$id) {
                return redirect()->route('masters.appointmenttime.index')
                    ->with('error', '無效的時段 ID');
            }

            $appointmenttime = AppointmentTime::findOrFail($id);

            return view('masters.appointmenttime.edit', compact('appointmenttime'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // 查無資料
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '找不到該時段資料');
        } catch (\Exception $e) {
            // 其他錯誤
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $hash_appointmenttime)
    {
        try {
            $id = Hashids::decode($hash_appointmenttime)[0] ?? null;

            if (!$id) {
                return redirect()->route('masters.appointmenttime.index')
                    ->with('error', '無效的時段 ID');
            }

            $appointmenttime = AppointmentTime::findOrFail($id);

            if (!$request->has('action')) {
                return redirect()->back()->with('error', '沒有選擇任何動作，請重新操作');
            }

            switch ($request->action) {
                case 'alter':
                    $validated = $request->validate([
                        'start_time' => 'required|after_or_equal:service_date',
                        'end_time' => 'required|after:start_time',
                        'service_item_id' => 'sometimes|exists:admin_service_items,id',
                    ], [
                        'start_time.required' => '請選擇開始時間',
                        'start_time.after_or_equal' => '開始時間必須在預約日期之後或相同',
                        'end_time.required' => '請選擇結束時間',
                        'end_time.after' => '結束時間需晚於開始時間',
                        'service_item_id.exists' => '所選服務項目不存在',
                    ]);

                    $changes = [];
                    foreach (['start_time', 'end_time'] as $key) {
                        if ($appointmenttime->$key != $validated[$key]) {
                            $changes[$key] = $validated[$key];
                        }
                    }

                    if (!empty($changes)) {
                        $appointmenttime->update($changes);
                    }

                    if (!empty($validated['service_item_id']) && $appointmenttime->schedulerecord) {
                        $sr = $appointmenttime->schedulerecord;
                        if ($sr->service_id != $validated['service_item_id']) {
                            $sr->service_id = $validated['service_item_id'];
                            $sr->save();
                        }
                    }

                    if (!empty($changes) || !empty($validated['service_item_id'])) {
                        return redirect()->route('masters.appointmenttime.index')
                            ->with('success', '時段更新成功');
                    }

                    return back()->with('error', '未發現任何變更');

                case 'accept':
                    $appointmenttime->status = 1;
                    $appointmenttime->save();
                    $this->sendAppointmentConfirmationEmail($appointmenttime, $request, $appointmenttime->user);
                    return redirect()->route('masters.appointmenttime.index')
                        ->with('success', '訂單已接受');

                case 'reject':
                    $appointmenttime->status = 3;
                    $appointmenttime->save();
                    $this->sendAppointmentConfirmationEmail($appointmenttime, $request, $appointmenttime->user);
                    return redirect()->route('masters.appointmenttime.index')
                        ->with('success', '訂單已拒絕');

                case 'cancel':
                    $appointmenttime->status = 4;
                    $appointmenttime->save();

                    if ($appointmenttime->schedulerecord) {
                        $appointmenttime->schedulerecord->status = 4;
                        $appointmenttime->schedulerecord->save();
                    }

                    return redirect()->route('masters.appointmenttime.index')
                        ->with('success', '訂單已取消');

                default:
                    return redirect()->back()->with('error', '未知的動作');
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '找不到該時段資料');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    private function sendAppointmentConfirmationEmail($appointmentTime, $request, $user)
    {
        try {
            $master = Auth::guard('master')->user(); // 記得要拿到 user

            if (!$master) {
                return;
            }

            $appointmentDetails = [
                'master_name' => $master->name,
                'user_name' => $user->name ?? '使用者',
                'service_date' => $appointmentTime->service_date ?? '',
                'status' => $appointmentTime->status ?? '',
                'appointment_time' => ($appointmentTime->start_time ?? '') . ' - ' . ($appointmentTime->end_time ?? ''),
                'service_address' => $appointmentTime->service_address ?? '',
            ];

            if (!empty($master->email)) {
                Mail::to($master->email)->send(new AppointmentConfirmation($appointmentDetails));
            }

            if (!empty($user->email)) {
                Mail::to($user->email)->send(new AppointmentConfirmation($appointmentDetails));
            }

        } catch (\Exception $e) {
            // 不拋出例外，避免影響主流程
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_appointmenttime)
    {
        try {
            $decoded = Hashids::decode($hash_appointmenttime);
            $id = $decoded[0] ?? null;

            if (!$id) {
                abort(404);
            }

            $appointmenttime = AppointmentTime::findOrFail($id);
            $appointmenttime->delete();

            return redirect()->route('masters.appointmenttime.index')
                ->with('success', '刪除成功');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '找不到要刪除的時段');
        } catch (\Exception $e) {
            \Log::error('刪除預約時段失敗', ['error' => $e->getMessage()]);
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '刪除時發生錯誤，請稍後再試');
        }
    }

    public function copy(Request $request)
    {
        try {
            $masterId = Auth::guard('master')->id();
            if (!$masterId) {
                return redirect()->route('masters.appointmenttime.index')
                    ->with('error', '未登入，無法複製時段');
            }

            $currentMonthStart = Carbon::now()->startOfMonth();
            $currentMonthEnd = Carbon::now()->endOfMonth();

            $monthOffset = intval($request->input('target_month', 1));
            $targetMonth = Carbon::now()->startOfMonth()->addMonthsNoOverflow($monthOffset);

            $sourceTimes = AppointmentTime::where('master_id', $masterId)
                ->whereBetween('service_date', [$currentMonthStart, $currentMonthEnd])
                ->get();

            if ($sourceTimes->isEmpty()) {
                return redirect()->route('masters.appointmenttime.index')
                    ->with('error', '本月無可複製的時段！');
            }

            $createdCount = 0;

            foreach ($sourceTimes as $time) {
                $sourceDate = Carbon::parse($time->service_date);
                $weekday = $sourceDate->dayOfWeek;
                $weekOfMonth = intval(($sourceDate->day - 1) / 7) + 1;

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
                    continue;
                }

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

            return redirect()->route('masters.appointmenttime.index')
                ->with('success', "成功複製 {$createdCount} 筆時段到下個月相同週次與星期！");

        } catch (\Exception $e) {
            \Log::error('複製時段失敗', ['error' => $e->getMessage()]);
            return redirect()->route('masters.appointmenttime.index')
                ->with('error', '複製時段時發生錯誤，請稍後再試');
        }
    }

}
