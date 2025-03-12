<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index()
    {

        return view('users.index');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('users.personal_information.edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
//            'phone' => ['required', 'regex:/^09\d{8}$/', 'size:10'], // 手機號碼 10 碼
//            'landline' => ['nullable', 'regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/'], // 市話選填
//            'address' => ['nullable', 'string', 'max:255'], // 地址
//            'line_id' => ['nullable', 'string', 'max:255'], // LINE ID 選填
//        ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'mobile' => ['required', 'regex:/^09\d{8}$/', 'size:10'], // 手機號碼 10 碼
            'phone' => ['nullable', 'regex:/^\(?0\d{1,2}\)?[- ]?\d{6,8}$/'], // 市話選填
            'address' => 'required|string|max:255',
            'line_id' => ['nullable', 'string', 'max:255'], // LINE ID 選填
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'line_id' => $request->input('line_id'),

        ]);

        return redirect()->route('users.personal_information.edit')->with('success', '個人資料更新成功');
    }
}
