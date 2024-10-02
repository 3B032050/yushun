<?php

namespace App\Http\Controllers;

use App\Models\ServiceArea;
use App\Http\Requests\StoreserviceareaRequest;
use App\Http\Requests\UpdateserviceareaRequest;

class ServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'major_area' => 'required|string',
            'minor_area' => 'required|string',
        ]);

        ServiceArea::create([
            'major_area' => $request->major_area,
            'minor_area' => $request->minor_area,
            'status' => 1, // 預設狀態
        ]);

        return redirect()->back()->with('success', '服務地區新增成功');
    }

    public function create()
    {
        return view('masters.admins.service_areas.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceArea $servicearea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceArea $servicearea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateserviceareaRequest $request, ServiceArea $servicearea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceArea $servicearea)
    {
        //
    }
}
