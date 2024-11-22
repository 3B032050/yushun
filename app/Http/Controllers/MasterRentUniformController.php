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
        $uniform = AdminUniform::findOrFail($request->uniform_id);
        $size = $request->size;

        $request->validate([
            'uniform_id' => 'required|exists:admin_uniforms,id',
            'size' => 'required|in:S,M,L,XL,XXL',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->quantity > $uniform->$size) {
            return back()->with('error', '該尺寸庫存不足，請減少數量或選擇其他尺寸！');
        }

        $master = Auth::guard('master')->user();

        RentUniform::create([
            'master_id' => $master->id,
            'uniform_id' => $request->input('uniform_id'),
            'size' => $request->input('size'),
            'quantity' => $request->input('quantity'),
            'status' => 1, //待處理
        ]);

        $uniform->decrement($size, $request->quantity);

        return redirect()->route('masters.rent_uniforms.index')->with('success', '租借成功，等待審核！');
    }


    /**
     * Display the specified resource.
     */
    public function history()
    {
        $master = Auth::guard('master')->user();

        $rentals = RentUniform::where('master_id', $master->id)
            ->with('uniform')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('masters.rent_uniforms.history', compact('rentals'));
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
