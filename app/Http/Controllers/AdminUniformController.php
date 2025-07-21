<?php

namespace App\Http\Controllers;

use App\Models\AdminUniform;
use App\Http\Requests\StoreUniformRequest;
use App\Http\Requests\UpdateUniformRequest;
use App\Models\Master;
use App\Models\RentUniform;
use Illuminate\Support\Facades\Storage;
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
        $masters = Master::whereDoesntHave('rentUniforms')->get();
        return view('admins.uniforms.create', compact('masters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUniformRequest $request)
    {
        $request->validate([
            'master_id' => 'required|exists:masters,id',
            'size' => 'required|string|max:10',
            'quantity' => 'required|integer|min:1',
        ]);

        // 避免重複新增
        if (RentUniform::where('master_id', $request->master_id)->exists()) {
            return redirect()->back()->with('error', '該師傅已經有借用紀錄');
        }

        RentUniform::create([
            'master_id' => $request->master_id,
            'size' => $request->size,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('admins.uniforms.index')->with('success', '成功新增制服紀錄');
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
    public function edit( $hash_uniform)
    {
        $id = Hashids::decode($hash_uniform)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $uniform = AdminUniform ::findOrFail($id);

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
        $id = Hashids::decode($hash_uniform)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $uniform = AdminUniform ::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'size_s' => 'required|integer|min:0',
            'size_m' => 'required|integer|min:0',
            'size_l' => 'required|integer|min:0',
            'size_xl' => 'required|integer|min:0',
            'size_xxl' => 'required|integer|min:0',
        ]);

        $imageName = $uniform->photo; // 保留原圖片名稱
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('uniforms', $imageName, 'public');
        }

        $uniform->update([
            'name' => $request->input('name'),
            'photo' => $imageName,
            'S' => $request->input('size_s'),
            'M' => $request->input('size_m'),
            'L' => $request->input('size_l'),
            'XL' => $request->input('size_xl'),
            'XXL' => $request->input('size_xxl'),
        ]);

        return redirect()->route('admins.uniforms.index')->with('success', '制服更新成功！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_uniform)
    {
        $id = Hashids::decode($hash_uniform)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $uniform = AdminUniform ::findOrFail($id);
        $this->destroy($uniform);

        return redirect()->route('admins.uniforms.index');
    }
}
