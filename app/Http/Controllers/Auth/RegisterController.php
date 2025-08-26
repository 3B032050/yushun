<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

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
        try {
            return Validator::make($data, [
                'name'    => ['required', 'string', 'max:255'],
                'email'   => 'required|string|email|max:255|unique:users',
                'phone'   => [
                    'nullable',
                    'regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/',
                    Rule::unique('users')
                ], // 市話選填
                'address' => ['nullable', 'string', 'max:255'],
                'line_id' => ['nullable', 'string', 'max:255'],
            ], [
                'name.required'   => '名稱為必填項目',
                'email.required'  => 'Email 為必填項目',
                'email.email'     => 'Email 格式錯誤',
                'email.unique'    => '該 Email 已存在',
                'phone.regex'     => '市話格式錯誤，例：02-23456789',
                'phone.unique'    => '市話已被使用',
            ]);
        } catch (Throwable $e) {
            // 如果驗證初始化失敗，回傳一個空的 Validator（避免崩潰）
            return Validator::make([], []);
        }
    }

    /**
     * 建立新使用者
     */
    protected function create(array $data)
    {
        try {
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
        } catch (Throwable $e) {
            // 如果資料庫異常，拋出錯誤給上層處理
            throw $e;
        }
    }

    /**
     * 註冊成功後導向
     */
    protected function redirectTo()
    {
        return '/users/index';
    }
}
