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

class MasterRegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/masters/index';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.masters_register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:masters'],
            'phone' => ['required', 'string', 'max:15'],
        ]);
    }

    protected function create(array $data)
    {
        return Master::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['phone']),
            'position' => '1',
        ]);
    }
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $master = $this->create($request->all());

        event(new Registered($master));

        Auth::guard('master')->login($master);

        return redirect()->route('masters.verification.notice');
    }
}
