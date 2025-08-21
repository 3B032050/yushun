<?php

namespace App\Http\Controllers;

use App\Models\master;
use App\Http\Requests\StoremasterRequest;
use App\Http\Requests\UpdatemasterRequest;
use App\Models\RentUniform;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('masters.index');
    }

    protected function authenticated(Request $request, $user)
    {
        if (Auth::guard('master')->check()) {
            return redirect()->intended(route('masters.index'));
        }
        return redirect()->route('masters_login');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoremasterRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(master $master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Master $master)
    {
        $master = Auth::guard('master')->user();

        $rental = RentUniform::where('master_id', $master->id)->first();;
        $data = ['rental' => $rental,
            'master' => $master];

        return view('masters.personal_information.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(UpdatemasterRequest $request, master $master)
//    {
//        $master = Auth::guard('master')->user();
//        // 檢查 Email 是否有變更
//
//
//        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|email|max:255|unique:masters,email,' . $master->id . ',id',
//            'phone' => 'required|digits:10',
//        ]);
//
//
//        $master->update([
//            'name' => $request->input('name'),
//            'email' => $request->input('email'),
//            'phone' => $request->input('phone'),
//            'password' => Hash::make($request['phone']),
//            //'email_verified_at' => $emailChanged ? null : $master->email_verified_at,
//        ]);

// 如果 Email 有變更，重設驗證狀態並寄出驗證信


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(master $master)
    {
        //
    }
}
