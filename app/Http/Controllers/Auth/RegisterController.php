<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/users/index';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 驗證註冊資料
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => 'required|string|email|max:255|unique:users',
            'mobile'  => [
                'required',
                'regex:/^09\d{8}$/',
                Rule::unique('users')
            ],
            'phone'   => [
                'nullable',
                'regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/',
                Rule::unique('users')
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'line_id' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required'   => '名稱為必填項目',
            'email.required'  => 'Email 為必填項目',
            'email.email'     => 'Email 格式錯誤',
            'email.unique'    => '該 Email 已存在',
            'mobile.required' => '手機號碼為必填項目',
            'mobile.regex'    => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
            'mobile.unique'   => '手機號碼已被使用',
            'phone.regex'     => '市話格式錯誤，例：02-23456789',
            'phone.unique'    => '市話已被使用',
        ]);
    }

    /**
     * 註冊流程
     */
    public function register(Request $request)
    {
        try {
            // 驗證 (失敗會自動丟 ValidationException, 自帶 $errors)
            $this->validator($request->all())->validate();

            // 建立使用者
            $user = $this->create($request->all());

            return redirect($this->redirectTo)
                ->with('success', '註冊成功！');

        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator) // ✅ 給 Blade @error 用
                ->with('validation_errors', $e->validator->errors()->all()); // ✅ 給 SweetAlert 用
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /**
     * 建立新使用者
     */
    protected function create(array $data)
    {
        return User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'mobile'       => $data['mobile'] ?? null,
            'phone'        => $data['phone'] ?? null,
            'address'      => $data['address'] ?? null,
            'line_id'      => $data['line_id'] ?? null,
            'is_recurring' => 1, // 預設非定期

            // 初始密碼設為手機號碼
            'password'     => Hash::make($data['mobile'] ?? '123456'),
        ]);
    }

    protected function redirectTo()
    {
        return '/users/index';
    }
}
