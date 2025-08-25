<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoremasterRequest;
use App\Http\Requests\UpdatemasterRequest;
use App\Models\Master;
use App\Models\RentUniform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class MasterPersonalInformationController extends Controller
{
    public function index()
    {
        try {
            $master = Auth::guard('master')->user();
            if (!$master) {
                return redirect()->route('masters.login')->with('error', '請先登入');
            }

            // 多筆制服（由新到舊）
            $uniforms = RentUniform::where('master_id', $master->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('masters.personal_information.index', [
                'master'   => $master,
                'uniforms' => $uniforms,
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', '系統發生錯誤，請稍後再試');
        }
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
    public function store(Request $request)
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
    public function edit()
    {
        $master = Auth::guard('master')->user();
        $hashedMasterId = Hashids::encode($master->id);
        $uniforms = RentUniform::where('master_id', $master->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = ['uniforms' => $uniforms,
            'master' => $master,'hashedMasterId' => $hashedMasterId];

        return view('masters.personal_information.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatemasterRequest $request,$hashedMasterId)
    {
        $masterId = Hashids::decode($hashedMasterId)[0] ?? null;

        if (!$masterId) {
            abort(404);
        }

        $master = Master::findOrFail($masterId);
        $emailChanged = $master->email !== $request['email'];
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:masters,email,' . $master->id . ',id',
            'phone' => 'required|digits:10',
            ]);

        $master->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request['phone']),
        ]);
        if ($emailChanged) {
            $master->email_verified_at = null;
            $master->save();

            $master->sendEmailVerificationNotification();// 先寄信

            Auth::guard('master')->logout();

            return redirect()->route('login')
                ->with('warning', '您的信箱已更新，請重新登入並驗證新信箱。');
        }

        return redirect()->route('masters.personal_information.edit')->with('success', '個人資料更新成功');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(master $master)
    {
        //
    }
}
