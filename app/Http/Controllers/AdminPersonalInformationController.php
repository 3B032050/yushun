<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatemasterRequest;
use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Validation\ValidationException;

class AdminPersonalInformationController extends Controller
{
    public function index()
    {
        try {
            $master = Auth::guard('master')->user();
            if (!$master) {
                return redirect()->route('masters.login')->with('error', '請先登入');
            }


            return view('admins.personal_information.index', [
                'master'   => $master,
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(master $master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        try {
            $master = Auth::guard('master')->user();

            if (!$master) {
                return redirect()->route('masters.login')->with('error', '請先登入');
            }

            $hashedMasterId = Hashids::encode($master->id);

            $data = [
                'master' => $master,
                'hashedMasterId' => $hashedMasterId
            ];

            return view('admins.personal_information.edit', $data);

        } catch (\Throwable $e) {
            // 可選擇回到上一頁並帶錯誤訊息
            return back()->with('error', '系統發生錯誤，請稍後再試: ' . $e->getMessage());
        }

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
                    'required',
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
            // 檢查 Email 是否有變更
            $emailChanged = $master->email !== $validatedData['email'];
            // 更新資料
            $master->update([
                'name'  => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'password' => Hash::make($validatedData['phone']),
            ]);
            // 如果 Email 有變更，重設驗證狀態並寄出驗證信
            if ($emailChanged) {
                $master->forceFill(['email_verified_at' => null])->save();
                $master->save();

                $freshMaster = $master->fresh(); // 確保資料最新
                $freshMaster->sendEmailVerificationNotification();

                Auth::guard('master')->logout();

                return redirect()->route('masters.login')->with('warning', '您的信箱已更新，請重新登入並驗證新信箱。');
            }
            return redirect()->route('admins.personal_information.index')
                ->with('success', '資料更新成功');

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
    public function destroy(master $master)
    {
        //
    }
}
