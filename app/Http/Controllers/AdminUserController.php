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
                'name'   => 'required|string|max:255',
                'email'  => 'required|email|max:150|unique:users,email',

                // 市話：可留空，允許有或沒有連字號
                'phone'  => [
                    'nullable',
                    'regex:/^0\d{1,3}-?\d{5,8}$/',
                    Rule::unique('users','phone'),
                ],

                // 手機：必填，因為要拿來當密碼
                'mobile' => [
                    'required',
                    'regex:/^09\d{8}$/',
                    Rule::unique('users','mobile'),
                ],

                'address'      => 'nullable|string|max:255',
                'line_id'      => 'nullable|string|max:100',

                // 客戶類型：0=定期，1=非定期
                'is_recurring' => ['required', Rule::in([0,1])],
            ], [
                'name.required'        => '名稱為必填項目',
                'email.required'       => 'Email 為必填項目',
                'email.email'          => 'Email 格式錯誤',
                'email.unique'         => '該 Email 已存在',

                'phone.unique'         => '電話已被使用',
                'phone.regex'          => '電話號碼格式錯誤，格式如「02-23456789」或「0223456789」',

                'mobile.required'      => '手機為必填項目',
                'mobile.regex'         => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
                'mobile.unique'        => '手機已被使用',

                'is_recurring.required'=> '請選擇客戶類型',
                'is_recurring.in'      => '客戶類型不正確',
            ]);

            $user = User::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'] ?? null,
                'mobile'       => $validated['mobile'],
                'password'     => Hash::make($validated['mobile']), // 密碼=手機
                'address'      => $validated['address'] ?? null,
                'line_id'      => $validated['line_id'] ?? null,
                'is_recurring' => (int)$validated['is_recurring'],
            ]);

            return redirect()
                ->route('admins.users.index')
                ->with('success', '使用者新增成功');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', '請修正表單錯誤後再試');
        } catch (\Throwable $e) {
            // 如果要除錯，可暫時 dd($e->getMessage());
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
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
            if (!$id) {
                return back()->with('error', '無效的使用者 ID');
            }

            $user = User::findOrFail($id);

            // 基本清理：去除空白、email 小寫
            $request->merge([
                'name'         => trim((string)$request->name),
                'email'        => trim(mb_strtolower((string)$request->email)),
                'phone'        => trim((string)$request->phone),
                'mobile'       => trim((string)$request->mobile),
                'address'      => trim((string)$request->address),
                'line_id'      => trim((string)$request->line_id),
            ]);

            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => [
                    'required','email','max:150',
                    Rule::unique('users','email')->ignore($id),
                ],
                // 市話可留空；允許 02-23456789 或 0223456789
                'phone'  => [
                    'nullable',
                    'regex:/^0\d{1,3}-?\d{5,8}$/',
                    Rule::unique('users','phone')->ignore($id),
                ],
                // 手機可留空（更新時不一定要改），若填就要驗證且唯一
                'mobile' => [
                    'nullable',
                    'regex:/^09\d{8}$/',
                    Rule::unique('users','mobile')->ignore($id),
                ],
                'address'      => 'nullable|string|max:255',
                'line_id'      => 'nullable|string|max:100',
                // 客戶類型 0=定期、1=非定期
                'is_recurring' => ['required', Rule::in([0, 1])],
            ], [
                'name.required'         => '名稱為必填項目',
                'email.required'        => 'Email 為必填項目',
                'email.email'           => 'Email 格式錯誤',
                'email.unique'          => '該 Email 已存在',

                'phone.unique'          => '電話已被使用',
                'phone.regex'           => '電話號碼格式錯誤，例：02-23456789 或 0223456789',

                'mobile.regex'          => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
                'mobile.unique'         => '手機已被使用',

                'is_recurring.required' => '請選擇客戶類型',
                'is_recurring.in'       => '客戶類型不正確',
            ]);

            // 組 payload
            $payload = [
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone']  ?? null,
                'mobile'       => $validated['mobile'] ?? null,
                'address'      => $validated['address'] ?? null,
                'line_id'      => $validated['line_id'] ?? null,
                'is_recurring' => (int)$validated['is_recurring'],
            ];

            // 若有填手機且與舊值不同，才同步更新密碼為手機
            if (!empty($validated['mobile']) && $validated['mobile'] !== $user->mobile) {
                $payload['password'] = Hash::make($validated['mobile']);
            }

            $user->update($payload);

            return redirect()
                ->route('admins.users.index')
                ->with('success', '使用者資料更新成功');

        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator) // 讓 $errors 在 Blade 顯示
                ->with('error', '請修正表單錯誤後再試');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
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
