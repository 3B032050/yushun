<?php

namespace App\Http\Controllers;

use App\Models\AdminUniform;
use App\Models\RentUniform;
use App\Http\Requests\StoreUniformRequest;
use App\Http\Requests\UpdateUniformRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Vinkla\Hashids\Facades\Hashids;

class MasterRentUniformController extends Controller
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
    public function create($uniform)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUniformRequest $request)
    {
        try {
            $master = Auth::guard('master')->user();

            $validated = $request->validate([
                'size' => [
                    'required',
                    Rule::in(['S','M','L','XL','XXL']),
                    // 關鍵：同一位師傅，同尺寸不可重複
                    Rule::unique('rent_uniforms', 'size')
                        ->where(fn($q) => $q->where('master_id', $master->id)),
                ],
                'quantity' => ['required','integer','min:1','max:50'],
            ], [
                'size.required'     => '請選擇尺寸',
                'size.in'           => '尺寸不在允許範圍內',
                'size.unique'       => '此尺寸已登記，請改用編輯或選擇其他尺寸',
                'quantity.required' => '請輸入數量',
                'quantity.integer'  => '數量必須為整數',
                'quantity.min'      => '數量至少為 1',
                'quantity.max'      => '數量最多 50',
            ]);

            RentUniform::create([
                'master_id' => $master->id,
                'size'      => $validated['size'],
                'quantity'  => $validated['quantity'],
            ]);

            return redirect()
                ->route('masters.personal_information.index')
                ->with('success', '制服新增成功');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->validator)->with('error', '請修正表單錯誤後再試');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    /**
     * Display the specified resource.
     */
    public function history()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash_uniform)
    {
        $id = Hashids::decode($hash_uniform)[0] ?? null;
        if (!$id) abort(404, '無效的制服資料 ID');

        $master = Auth::guard('master')->user();

        // 僅能編輯自己的資料
        $uniform = RentUniform::where('id', $id)
            ->where('master_id', $master->id)
            ->firstOrFail();

        return view('masters.rent_uniforms.edit', [
            'master'  => $master,
            'uniform' => $uniform,
            'hashId'  => $hash_uniform, // 也可在 view 內重新 encode
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUniformRequest $request, $hash_uniform)
    {
        try {
            $id = Hashids::decode($hash_uniform)[0] ?? null;
            if (!$id) return back()->with('error', '無效的制服資料 ID');

            $master = Auth::guard('master')->user();

            $uniform = RentUniform::where('id', $id)
                ->where('master_id', $master->id)
                ->firstOrFail();

            $validated = $request->validate([
                'size' => [
                    'required',
                    Rule::in(['S','M','L','XL','XXL']),
                    // 同師傅 + 同尺寸 不可重複（更新時忽略自己）
                    Rule::unique('rent_uniforms', 'size')
                        ->where(fn($q) => $q->where('master_id', $master->id))
                        ->ignore($uniform->id),
                ],
                'quantity' => ['required','integer','min:1','max:50'],
            ], [
                'size.required'     => '請選擇尺寸',
                'size.in'           => '尺寸不在允許範圍內',
                'size.unique'       => '此尺寸已登記，不能重複',
                'quantity.required' => '請輸入數量',
                'quantity.integer'  => '數量必須為整數',
                'quantity.min'      => '數量至少為 1',
                'quantity.max'      => '數量最多 50',
            ]);

            $uniform->update($validated);

            return redirect()
                ->route('masters.personal_information.index')
                ->with('success', '制服資料更新成功');
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', '請修正表單錯誤後再試');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentUniform $uniform)
    {
        //
    }

    public function return(Request $request, $id)
    {
        $rental = RentUniform::findOrFail($id);

        $rental->status = 2;
        $rental->save();

        return redirect()->route('masters.rent_uniforms.history')->with('success', '租借狀態已更新為已歸還');
    }
}
