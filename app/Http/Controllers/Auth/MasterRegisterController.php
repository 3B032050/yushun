<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MasterRegisterController extends Controller
{
    protected $redirectTo = '/masters/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function showRegistrationForm()
    {
        return view('auth.masters_register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:masters',
            'phone' => ['required', 'string', 'max:15'],
        ]);
        if (Master::where('phone', $request->phone)->exists()) {
            return redirect()->back()->with('error', '此電話號碼已經存在');
        }
        else
        {
            Master::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->phone),
                'position'=>'1'
            ]);
        };

        return redirect()->route('masters_login');
    }
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => 'required|string|email|max:255|unique:masters',
//            'phone' => ['required', 'string', 'max:15'],
//
//        ]);
//    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
//    protected function create(array $data)
//    {
//        return Master::create([
//            'name' => $data['name'],
//            'email' => $data['email'],
//            'phone' => $data['phone'],
//
//            'password' => Hash::make($data['phone']),
//            'position'=>'0',
//        ]);
//    }

//    public function register(Request $request)
//    {
//        $this->validator($request->all())->validate();
//
//        $master = $this->create($request->all());
//
//        auth()->login($master);
//
//        return redirect($this->redirectTo);
//    }
}
