<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\ScheduleRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index()
    {
        $masters = Master::all();

        return view('admins.schedules.index', compact('masters'));
    }

    public function getScheduleData(Request $request)
    {
        $masterId = $request->input('master_id');

        $schedules = ScheduleRecord::where('master_id', $masterId)->get();

        $formattedSchedules = $schedules->map(function ($schedule) {
            // 確保有日期，如果沒有 `service_date`，則使用 `appointment_time` 的日期部分
            $serviceDate = $schedule->service_date ?? now()->toDateString();

            return [
                'title' => '詳細資訊',
                'start' => $serviceDate,  // 確保行事曆有日期
                'color' => $schedule->status == 0 ? '#28a745' : '#dc3545',
                'extendedProps' => [
                    'time' => $schedule->appointment_time ?? '未提供時間',
                    'price' => $schedule->price ?? '未提供',
                    'service' => $schedule->service->name ?? '未提供',
                    'customer' => $schedule->user ? $schedule->user->name : '未知用戶',
                    'description' => match ($schedule->status) {
                        0 => '待確認',
                        1 => '已確認',
                        2 => '已完成',
                        3 => '不成立',
                        4 => '已取消',
                        default => '未知狀態'
                    },
                    'score' => $schedule->scheduledetail->score ?? null,
                    'comment' => $schedule->scheduledetail->comment ?? null,
                    'before_photo' => $schedule->scheduledetail->before_photo ?? null,
                    'after_photo' => $schedule->scheduledetail->after_photo ?? null,
                ]
            ];
        });

        return response()->json($formattedSchedules);
    }


}
