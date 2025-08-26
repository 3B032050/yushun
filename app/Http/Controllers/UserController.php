<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('users.index');
    }

    public function personal_index()
    {
        try {
            $user = auth()->user();
            return view('users.personal_information.index', compact('user'));
        } catch (\Throwable $e) {
            return back()->with('error', '載入個人資料時發生錯誤，請稍後再試');
        }
    }

    public function edit($hash_user)
    {
        try {
            $id = Hashids::decode($hash_user)[0] ?? null;

            if (!$id) {
                return redirect()->route('users.personal_information.personal_index')
                    ->with('error', '無效的使用者 ID');
            }

            $user = User::findOrFail($id);

            return view('users.personal_information.edit', ['user' => $user]);
        } catch (\Throwable $e) {
            return back()->with('error', '載入編輯頁面時發生錯誤，請稍後再試');
        }
    }

    public function update(Request $request, $hash_user)
    {
        try {
            $id = Hashids::decode($hash_user)[0] ?? null;
            if (!$id) {
                return back()->with('error', '無效的使用者 ID');
            }

            $user = User::findOrFail($id);

            // 先算出 email 是否有變更（等一下驗證會擋格式/唯一性）
            $emailChanged = $user->email !== $request->input('email');

            // 驗證：失敗會丟 ValidationException（我們在下方單獨 catch）
            $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'mobile'  => ['nullable','regex:/^09\d{8}$/', Rule::unique('users','mobile')->ignore($user->id)],
                'phone'   => ['nullable','regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/'],
                'address' => 'required|string|max:255',
                'line_id' => ['nullable','string','max:255'],
            ], [
                'name.required'     => '名稱為必填項目',
                'email.required'    => 'Email 為必填項目',
                'email.email'       => 'Email 格式錯誤',
                'email.unique'      => '該 Email 已存在',
                'mobile.regex'      => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
                'mobile.unique'     => '手機號碼已被使用',
                'phone.regex'       => '市話格式錯誤，例：02-23456789',
                'address.required'  => '地址為必填項目',
                'line_id.string'    => 'LINE ID 格式不正確',
                'line_id.max'       => 'LINE ID 長度過長',
            ]);

            // 更新資料；有填 mobile 才同步當新密碼
            $payload = [
                'name'    => $request->input('name'),
                'email'   => $request->input('email'),
                'mobile'  => $request->input('mobile'),
                'phone'   => $request->input('phone'),
                'address' => $request->input('address'),
                'line_id' => $request->input('line_id'),
            ];
            if ($request->filled('mobile')) {
                $payload['password'] = Hash::make($request->input('mobile'));
            }

            $user->update($payload);

            if ($emailChanged) {
                $user->email_verified_at = null;
                $user->save();

                $user->sendEmailVerificationNotification();
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with('warning', '您的信箱已更新，請重新登入並驗證新信箱。');
            }

            return redirect()
                ->route('users.personal_information.personal_index')
                ->with('success', '個人資料更新成功');

        } catch (ValidationException $e) {
            // 關鍵：把欄位錯誤帶回去，Blade 的 $errors 才會顯示逐欄位訊息
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', '請修正表單錯誤後再試');
        } catch (\Throwable $e) {
            // 其他非驗證型錯誤才走這裡
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }
}
