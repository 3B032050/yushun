<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoremasterRequest;
use App\Http\Requests\UpdatemasterRequest;
use App\Models\Master;
use App\Models\RentUniform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class MasterPersonalInformationController extends Controller
{
    public function index()
    {
        $master = Auth::guard('master')->user();

        $rental = RentUniform::where('master_id', $master->id)->first();;
        $data = ['rental' => $rental,
            'master' => $master];

        return view('masters.personal_information.index',$data);
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
    public function edit()
    {
        $master = Auth::guard('master')->user();
        $hashedMasterId = Hashids::encode($master->id);
        $rental = RentUniform::where('master_id', $master->id)->first();;
        $data = ['rental' => $rental,
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

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:masters,email,' . $master->id . ',id',
            'phone' => 'required|digits:10',
            ]);

        $master->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->route('masters.personal_information.index')->with('success', '個人資料更新成功');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(master $master)
    {
        //
    }
}
