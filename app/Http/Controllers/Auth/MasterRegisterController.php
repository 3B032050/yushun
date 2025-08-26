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

    protected function validator(array $data)
    {
        // 這裡維持原本回傳 Validator 物件的做法，訊息在 register() 捕捉
        return Validator::make($data, [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:masters,email'],
            'phone' => ['required', 'string', 'max:15'],
        ], [
            'name.required'   => '姓名為必填',
            'email.required'  => 'Email 為必填',
            'email.email'     => 'Email 格式不正確',
            'email.unique'    => '此 Email 已被註冊',
            'phone.required'  => '電話為必填',
        ]);
    }

    protected function create(array $data)
    {
        return Master::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            // 預設密碼為電話（你原本的規格）
            'password' => Hash::make($data['phone']),
            // 1 = 可以使用師傅端功能
            'position' => '1',
        ]);
    }

    public function register(Request $request)
    {
        try {
            // 1) 驗證（失敗會丟 ValidationException）
            $this->validator($request->all())->validate();

            // 2) 建立師傅
            $master = $this->create($request->all());

            // 3) 觸發註冊事件（若後續有寄信或其他 listener）
            event(new Registered($master));

            // 4) 以 master guard 登入
            Auth::guard('master')->login($master);

            // 5) 導向驗證通知頁（你原本的行為）
            return redirect()->route('masters.verification.notice');

        } catch (ValidationException $e) {
            // 表單驗證錯誤：回填欄位並顯示對應錯誤
            return back()
                ->withInput()
                ->withErrors($e->validator)
                ->with('error', '請修正表單錯誤後再試');
        } catch (Throwable $e) {
            // 其他例外（DB/系統等）
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }
}
