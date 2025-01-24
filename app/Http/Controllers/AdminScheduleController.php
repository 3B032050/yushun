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
            $timeRange = explode(' - ', $schedule->appointment_time);
            $startTime = isset($timeRange[0]) ? $timeRange[0] : '00:00';
            $endTime = isset($timeRange[1]) ? $timeRange[1] : '00:00';

            return [
                'title' => '客戶：' . ($schedule->user ? $schedule->user->name : '未知用戶'),
                'start' => $schedule->service_date . 'T' . $startTime,
                'end' => $schedule->service_date . 'T' . $endTime,
                'description' => '狀態：' . ($schedule->status == 0 ? '已確認' : '待確認'),
                'color' => $schedule->status == 0 ? '#28a745' : '#dc3545',
            ];
        });
        return response()->json($formattedSchedules);
    }
}
