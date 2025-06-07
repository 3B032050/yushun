<?php

namespace App\Http\Controllers;

use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class AdminMasterController extends Controller
{
    public function index()
    {
        $masters = Master::where('position', '1')->get();

        $data = ['masters' => $masters];

        return view('admins.masters.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.masters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 驗證表單輸入
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:masters,email',
            'phone' => 'required|string|max:15',
        ], [
            'name.required' => '名稱為必填項目',
            'email.required' => 'Email 為必填項目',
            'email.unique' => '該 Email 已存在',
            'phone.required' => '電話為必填項目',
        ]);

        $master = Master::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'position' => 1,
            'password' => Hash::make($validatedData['phone']),
        ]);

        if (! $master->hasVerifiedEmail()) {
            $master->markEmailAsVerified();
        }
        // 回傳成功訊息並重導至列表頁
        return redirect()->route('admins.masters.index')->with('success', '師傅新增成功');
    }

    /**
     * Display the specified resource.
     */
    public function show(Master $master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash_master)
    {
        $id = Hashids::decode($hash_master)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $master = Master ::findOrFail($id);

        $data = [
            'master'=> $master,
        ];

        return view('admins.masters.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$hash_master)
    {
        $id = Hashids::decode($hash_master)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $master = Master ::findOrFail($id);
        // 驗證表單輸入
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:masters,email,' . $master->id . ',id',
            'phone' => 'required|string|max:15',
        ]);

        // 更新師傅資料
        $master->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        // 回傳成功訊息並重導至列表頁
        return redirect()->route('admins.masters.index')->with('success', '師傅資料更新成功');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_master)
    {
        $id = Hashids::decode($hash_master)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $master = Master ::findOrFail($id);
        $master->delete();

        return redirect()->route('admins.masters.index');
    }
}
