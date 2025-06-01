<?php

namespace App\Http\Controllers;

use App\Models\AdminUniform;
use App\Models\RentUniform;
use App\Http\Requests\StoreUniformRequest;
use App\Http\Requests\UpdateUniformRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class MasterRentUniformController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $uniforms = AdminUniform::all();

        $data = ['uniforms' => $uniforms];

        return view('masters.rent_uniforms.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($uniform)
    {
        $uniform = AdminUniform::findOrFail($uniform);

        $data = ['uniform' => $uniform];
        return view('masters.rent_uniforms.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUniformRequest $request)
    {
        $request->validate([
            'size' => 'required|in:S,M,L,XL,XXL',
            'quantity' => 'required|integer|min:1',
        ]);


        $master = Auth::guard('master')->user();

        RentUniform::create([
            'master_id' => $master->id,
            'size' => $request->input('size'),
            'quantity' => $request->input('quantity'),
        ]);

        return redirect()->route('masters.personal_information.index');
    }


    /**
     * Display the specified resource.
     */
    public function history()
    {
        $master = Auth::guard('master')->user();

        $rental = RentUniform::where('master_id', $master->id)->first();;
        $data = ['rental' => $rental];
        return view('masters.rent_uniforms.history',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentUniform $uniform)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUniformRequest $request, RentUniform $uniform)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentUniform $uniform)
    {
        //
    }

    public function return(Request $request, $id)
    {
        $rental = RentUniform::findOrFail($id);

        $rental->status = 2;
        $rental->save();

        return redirect()->route('masters.rent_uniforms.history')->with('success', '租借狀態已更新為已歸還');
    }
}
