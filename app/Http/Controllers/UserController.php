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
            // 1) 解碼 hash ID
            $id = Hashids::decode($hash_user)[0] ?? null;
            if (!$id) {
                return back()->with('error', '無效的使用者 ID');
            }

            // 2) 取得使用者
            $user = User::findOrFail($id);

            // 3) 正規化輸入（去空白 / email 小寫）
            $request->merge([
                'name'    => trim((string) $request->name),
                'email'   => trim(mb_strtolower((string) $request->email)),
                'mobile'  => trim((string) $request->mobile),
                'phone'   => trim((string) $request->phone),
                'address' => trim((string) $request->address),
                'line_id' => trim((string) $request->line_id),
            ]);

            // 4) 先判斷 email 是否變更（用正規化後的值）
            $emailChanged = $user->email !== $request->input('email');

            // 5) 驗證（失敗會丟 ValidationException）
            $request->validate([
                'name'    => 'bail|required|string|max:255',
                // 想只做格式就用 email:rfc；想順便檢查網域存在就用 email:rfc,dns
                'email'   => [
                    'bail', 'required', 'string', 'max:255', 'email:rfc,dns',
                    Rule::unique('users','email')->ignore($user->id),
                ],
                'mobile'  => [
                    'bail', 'nullable', 'regex:/^09\d{8}$/',
                    Rule::unique('users','mobile')->ignore($user->id),
                ],
                'phone'   => ['bail','nullable','regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/'],
                'address' => 'bail|required|string|max:255',
                'line_id' => 'bail|nullable|string|max:255',
            ], [
                'name.required'     => '名稱為必填項目',

                'email.required'    => 'Email 為必填項目',
                'email.email'       => 'Email 格式錯誤或網域不存在',
                'email.unique'      => '該 Email 已存在',

                'mobile.regex'      => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
                'mobile.unique'     => '手機號碼已被使用',

                'phone.regex'       => '市話格式錯誤，例：02-23456789',

                'address.required'  => '地址為必填項目',

                'line_id.string'    => 'LINE ID 格式不正確',
                'line_id.max'       => 'LINE ID 長度過長',
            ]);

            // 6) 準備更新資料；有填 mobile 才同步當新密碼
            $payload = [
                'name'    => $request->input('name'),
                'email'   => $request->input('email'),
                'mobile'  => $request->input('mobile') ?: null,
                'phone'   => $request->input('phone') ?: null,
                'address' => $request->input('address') ?: null,
                'line_id' => $request->input('line_id') ?: null,
            ];
            if ($request->filled('mobile')) {
                $payload['password'] = Hash::make($request->input('mobile'));
            }

            $user->update($payload);

            // 7) Email 有變更：重設驗證、寄驗證信並請使用者重新登入
            if ($emailChanged) {
                $user->email_verified_at = null;
                $user->save();

                $user->sendEmailVerificationNotification();
                Auth::logout();

                return redirect()
                    ->route('login')
                    ->with('warning', '您的信箱已更新，請重新登入並驗證新信箱。');
            }

            // 8) 成功
            return redirect()
                ->route('users.personal_information.personal_index')
                ->with('success', '個人資料更新成功');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        } catch (\Throwable $e) {
            // 其他非驗證型錯誤
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }
}
