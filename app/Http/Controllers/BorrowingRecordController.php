<?php

namespace App\Http\Controllers;

use App\Models\AppointmentTime;
use App\Models\BorrowingRecord;
use App\Http\Requests\StoreborrowingrecordRequest;
use App\Http\Requests\UpdateborrowingrecordRequest;
use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class BorrowingRecordController extends Controller
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
    public function create(AppointmentTime $appointmenttime)
    {
        $equipments = Equipment::all();

        $data = ['equipments' => $equipments];

        return view('masters.borrow_equipments.create',$data, compact('appointmenttime'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreborrowingrecordRequest $request,AppointmentTime $appointmenttime)
    {
        $masterId = Auth::guard('master')->id();

        // 驗證請求資料（確保設備陣列正確）
        $validated = $request->validate([
            'equipment_ids' => 'required|array', // 多筆設備 ID
            'equipment_ids.*' => 'exists:equipment,id', // 每個 ID 必須存在
            'borrow_quantities' => 'nullable|array', // 數量欄位可選填
            'borrow_quantities.*' => 'nullable|integer|min:1', // 設備數量可為空，但填寫時至少為 1
        ]);

        // 迭代所有設備，逐筆處理
        foreach ($validated['equipment_ids'] as $index => $equipment_id) {
            $quantity = $validated['borrow_quantities'][$index] ?? 0; // 取得對應設備的數量，沒填則為 0
            $equipment = Equipment::findOrFail($equipment_id);

            // 如果設備數量為 0，則跳過這筆
            if ($quantity > 0) {
                // 檢查設備是否足夠
                if ($equipment->quantity < $quantity) {
                    return redirect()->back()->with('error', "設備 {$equipment->name} 的數量不足！");
                }

                // 新增借用記錄
                BorrowingRecord::create([
                    'master_id' => $masterId,
                    'equipment_id' => $equipment_id,
                    'appointment_time_id' => $appointmenttime->id,
                    'quantity' => $quantity,
                    'status' => 0, // 0 = 借出中
                    'borrowing_date' => now(), // 借用日期為當前時間
                    'return_date' => now(), // 歸還日期為 null
                ]);

                // 更新設備數量
                $equipment->decrement('quantity', $quantity);
            }
        }

        return redirect()->route('masters.appointmenttime.index')->with('success', '設備借用成功！');
    }


    /**
     * Display the specified resource.
     */
    public function show(BorrowingRecord $borrowingrecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowingRecord $borrowingrecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateborrowingrecordRequest $request, BorrowingRecord $borrowingrecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowingRecord $borrowingrecord)
    {
        //
    }
}
