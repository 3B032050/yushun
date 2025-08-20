<?php

namespace App\Http\Controllers;

use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Validation\ValidationException;

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
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:masters,email',
                'phone' => ['nullable', 'regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/', Rule::unique('masters')],
            ], [
                'name.required'  => '名稱為必填項目',
                'email.required' => 'Email 為必填項目',
                'email.unique'   => '該 Email 已存在',
                'phone.regex'   => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
                'phone.unique'   => '電話號碼已被使用',
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

            return redirect()->route('admins.masters.index')->with('success', '師傅新增成功');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        }
        catch (\Exception $e) {
            // 其他系統錯誤
            return redirect()->back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }


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
    public function update(Request $request, $hash_master)
    {
        try {
            // 解碼 hash ID
            $id = Hashids::decode($hash_master)[0] ?? null;
            if (!$id) {
                return redirect()->back()->with('error', '無效的師傅 ID');
            }

            $master = Master::findOrFail($id);

            // 驗證輸入
            $validatedData = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('masters')->ignore($master->id),
                ],
                'phone' => [
                    'nullable',
                    'regex:/^09\d{8}$/',

                Rule::unique('masters')->ignore($master->id),
                ],
            ], [
                'name.required'  => '名稱為必填項目',
                'email.required' => 'Email 為必填項目',
                'email.unique'   => '該 Email 已存在',
                'phone.regex'   => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
                'phone.unique'   => '電話號碼已被使用',
            ]);

            // 更新資料
            $master->update([
                'name'  => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
            ]);

            return redirect()->route('admins.masters.index')
                ->with('success', '師傅資料更新成功');

        }catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        }
        catch (\Exception $e) {
            // 其他系統錯誤
            return redirect()->back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_master)
    {
        try {
            $id = Hashids::decode($hash_master)[0] ?? null;

            if (!$id) {
                return redirect()->back()->with('error', '無效的師傅 ID');
            }

            $master = Master::findOrFail($id);
            $master->delete();

            return redirect()->route('admins.masters.index')
                ->with('success', '師傅資料刪除成功');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '系統發生錯誤，無法刪除');
        }
    }

}
