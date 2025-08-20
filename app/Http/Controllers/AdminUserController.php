<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Vinkla\Hashids\Facades\Hashids;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();

        $data = ['users' => $users];

        return view('admins.users.index',$data);
    }

    /** 新增表單 */
    public function create()
    {
        return view('admins.users.create');
    }

    /** 儲存 */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:150|unique:users,email',
                'phone'    => ['nullable','string','max:30', Rule::unique('users','phone')],
                'mobile'   => ['nullable','string','max:30', Rule::unique('users','mobile')],
                'address'  => 'nullable|string|max:255',
                'line_id'  => 'nullable|string|max:100',
            ], [
                'name.required'      => '名稱為必填項目',
                'email.required'     => 'Email 為必填項目',
                'email.email'        => 'Email 格式錯誤',
                'email.unique'       => '該 Email 已存在',
                'phone.unique'       => '電話已被使用',
                'mobile.unique'      => '手機已被使用',
            ]);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'] ?? null,
                'mobile'   => $validated['mobile'] ?? null,
                'password' => Hash::make($validated['mobile']),
                'address'  => $validated['address'] ?? null,
                'line_id'  => $validated['line_id'] ?? null,
            ]);

            // 如有啟用驗證可開：$user->sendEmailVerificationNotification();

            return redirect()->route('admins.users.index')->with('success', '使用者新增成功');
        } catch (ValidationException $e) {
            return back()->withInput()->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /** 編輯表單 */
    public function edit($hash_user)
    {
        $id = Hashids::decode($hash_user)[0] ?? null;
        if (!$id) abort(404);

        $user = User::findOrFail($id);
        return view('admins.users.edit', ['user' => $user]);
    }

    /** 更新 */
    public function update(Request $request, $hash_user)
    {
        try {
            $id = Hashids::decode($hash_user)[0] ?? null;
            if (!$id) return back()->with('error', '無效的使用者 ID');

            $user = User::findOrFail($id);

            // 基本清理（避免空白造成重複判斷誤差）
            $request->merge([
                'name'    => trim((string)$request->name),
                'email'   => trim(mb_strtolower((string)$request->email)),
                'phone'   => trim((string)$request->phone),
                'mobile'  => trim((string)$request->mobile),
                'address' => trim((string)$request->address),
                'line_id' => trim((string)$request->line_id),
            ]);

            $validated = $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => ['required','email','max:150', Rule::unique('users','email')->ignore($user->id)],
                'phone'    => ['nullable','string','max:30', Rule::unique('users','phone')->ignore($user->id)],
                'mobile'   => ['string','max:30', Rule::unique('users','mobile')->ignore($user->id)],
                'address'  => 'nullable|string|max:255',
                'line_id'  => 'nullable|string|max:100',
            ], [
                'name.required'  => '名稱為必填項目',
                'email.required' => 'Email 為必填項目',
                'email.email'    => 'Email 格式錯誤',
                'email.unique'   => '該 Email 已存在',
                'phone.unique'   => '電話已被使用',
                'mobile.unique'  => '手機已被使用',
            ]);

            // 組 payload
            $payload = [
                'name'    => $validated['name'],
                'email'   => $validated['email'],
                'phone'   => $validated['phone'] ?? null,
                'mobile'  => $validated['mobile'] ?? null,
                'address' => $validated['address'] ?? null,
                'line_id' => $validated['line_id'] ?? null,
            ];

            if (!empty($validated['mobile'])) {
                $payload['password'] = Hash::make($validated['mobile']);
            }

            $user->update($payload);

            return redirect()->route('admins.users.index')->with('success', '使用者資料更新成功');
        } catch (ValidationException $e) {
            return back()->withInput()->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /** 刪除 */
    public function destroy($hash_user)
    {
        try {
            $id = Hashids::decode($hash_user)[0] ?? null;
            if (!$id) return back()->with('error', '無效的使用者 ID');

            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('admins.users.index')->with('success', '使用者資料刪除成功');
        } catch (\Exception $e) {
            return back()->with('error', '系統發生錯誤，無法刪除');
        }
    }
}
