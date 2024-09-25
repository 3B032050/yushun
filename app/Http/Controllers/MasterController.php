<?php

namespace App\Http\Controllers;

use App\Models\master;
use App\Http\Requests\StoremasterRequest;
use App\Http\Requests\UpdatemasterRequest;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        return view('masters.personal_information.edit', ['master' => $master]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatemasterRequest $request, master $master)
    {
        //
        $master = Auth::guard('master')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $master->id,
            'phone' => 'required|numeric|digits:10',
        ]);

        $master->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

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
