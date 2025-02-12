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
//            $timeRange = explode(' - ', $schedule->appointment_time);
//            $startTime = isset($timeRange[0]) ? trim($timeRange[0]) : '00:00:00';
//            $endTime = isset($timeRange[1]) ? trim($timeRange[1]) : '00:00:00';

            return [
                'title' => '客戶：' . ($schedule->user ? $schedule->user->name : '未知用戶') . ' - ' . ($schedule->service->name ?? '未提供'),
                'start' => $schedule->service_date,
                'color' => $schedule->status == 0 ? '#28a745' : '#dc3545',
                'extendedProps' => [
                    'time' => $schedule->appointment_time,
                    'price' => $schedule->service->price ?? '未提供',
                    'service' => $schedule->service->name ?? '未提供',
                    'description' => match ($schedule->status) {
                        0 => '已確認',
                        1 => '待確認',
                        2 => '已完成',
                        3 => '不成立',
                        4 => '已取消',
                        default => '未知狀態'
                    }
                ]
            ];

        });

        return response()->json($formattedSchedules);
    }

}
