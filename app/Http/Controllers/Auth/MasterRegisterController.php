<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class MasterRegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/masters/index';

    public function __construct()
    {
        $this->middleware('guest:master');
    }

    public function showRegistrationForm()
    {
        return view('auth.masters_register');
    }

    /**
     * 驗證規則
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:masters,email',
            'phone' => [
                'required',
                'regex:/^09\d{8}$/',
                'unique:masters,phone',
            ],
        ], [
            'name.required'   => '名稱為必填項目',
            'email.required'  => 'Email 為必填項目',
            'email.email'     => 'Email 格式錯誤',
            'email.unique'    => '該 Email 已存在',
            'phone.required'  => '手機號碼為必填項目',
            'phone.regex'     => '手機號碼格式錯誤，須為 09 開頭共 10 碼',
            'phone.unique'    => '電話號碼已被使用',
        ]);
    }

    /**
     * 建立師傅
     */
    protected function create(array $data)
    {
        return Master::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            // 預設密碼為電話
            'password' => Hash::make($data['phone']),
            'position' => '1', // 1 = 可以使用師傅端功能
        ]);
    }

    /**
     * 註冊流程
     */
    public function register(Request $request)
    {
        try {
            // 1) 驗證
            $this->validator($request->all())->validate();

            // 2) 建立師傅
            $master = $this->create($request->all());

            // 3) 觸發事件（如寄信）
            event(new Registered($master));

            // 4) 自動登入
            Auth::guard('master')->login($master);

            // 5) 導向驗證通知頁
            return redirect()->route('masters.verification.notice');

        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('validation_errors', $e->validator->errors()->all());
        } catch (Throwable $e) {
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }
}
