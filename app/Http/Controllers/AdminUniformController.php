<?php

namespace App\Http\Controllers;

use App\Models\AdminUniform;
use App\Http\Requests\StoreUniformRequest;
use App\Http\Requests\UpdateUniformRequest;
use App\Models\Master;
use App\Models\RentUniform;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Vinkla\Hashids\Facades\Hashids;

class AdminUniformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rent_uniforms = RentUniform::all();

        $data = ['rent_uniforms' => $rent_uniforms];

        return view('admins.uniforms.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masters = Master::orderBy('name')->where('id','!=', auth()->id())->get();
        return view('admins.uniforms.create', compact('masters'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUniformRequest $request)
    {
        try {
            $validated = $request->validate([
                'master_id' => [
                    'required',
                    'integer',
                    'exists:masters,id',
                    // 關鍵：同一位師傅只能有一筆制服資料
                    Rule::unique('rent_uniforms', 'master_id'),
                ],
                'size'     => ['required', Rule::in(['S','M','L','XL','XXL'])],
                'quantity' => ['required','integer','min:1','max:50'],
            ], [
                'master_id.required' => '請選擇師傅',
                'master_id.exists'   => '所選師傅不存在',
                'master_id.unique'   => '此師傅已登記過制服，請改用「編輯」修改尺寸與數量',
                'size.required'      => '請選擇尺寸',
                'size.in'            => '尺寸不在允許範圍內',
                'quantity.required'  => '請輸入數量',
                'quantity.integer'   => '數量必須為整數',
                'quantity.min'       => '數量至少為 1',
                'quantity.max'       => '數量最多 50',
            ]);

            RentUniform::create($validated);

            return redirect()
                ->route('admins.uniforms.index')
                ->with('success', '制服資料新增成功');
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
     * Display the specified resource.
     */
    public function show(AdminUniform $uniform)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash_uniform)
    {
        $id = Hashids::decode($hash_uniform)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $uniform = RentUniform::findOrFail($id);

        $data = [
            'uniform'=> $uniform,
        ];

        return view('admins.uniforms.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUniformRequest $request, $hash_uniform)
    {
        try {
            $id = Hashids::decode($hash_uniform)[0] ?? null;
            if (!$id) {
                return back()->with('error', '無效的制服資料 ID');
            }

            $rent_uniform = RentUniform::findOrFail($id);

            // 只允許改尺寸與數量，不允許改 master_id
            // 若表單仍帶了 master_id 就直接忽略；或你想更嚴格可直接擋下：
            if ($request->filled('master_id') && (int)$request->master_id !== (int)$rent_uniform->master_id) {
                return back()->withInput()->with('error', '不允許更換所屬師傅');
            }

            $validated = $request->validate([
                'size'     => ['required', Rule::in(['S','M','L','XL','XXL'])],
                'quantity' => ['required','integer','min:1','max:50'],
            ], [
                'size.required'      => '請選擇尺寸',
                'size.in'            => '尺寸不在允許範圍內',
                'quantity.required'  => '請輸入數量',
                'quantity.integer'   => '數量必須為整數',
                'quantity.min'       => '數量至少為 1',
                'quantity.max'       => '數量最多 50',
            ]);

            $rent_uniform->update([
                'size'     => $validated['size'],
                'quantity' => $validated['quantity'],
            ]);

            return redirect()
                ->route('admins.uniforms.index')
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
    public function destroy($hash_uniform)
    {
        try {
            $id = Hashids::decode($hash_uniform)[0] ?? null;
            if (!$id) {
                return redirect()->back()->with('error', '無效的制服資料 ID');
            }

            $uniform = RentUniform::findOrFail($id);
            $uniform->delete();

            return redirect()
                ->route('admins.uniforms.index')
                ->with('success', '制服資料刪除成功');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', '系統發生錯誤，無法刪除');
        }
    }
}
