<?php

namespace App\Http\Controllers;
use App\Models\Master;
use App\Models\AdminServiceArea;
use App\Models\MasterServiceArea;
use App\Http\Requests\StoreMasterServiceAreaRequest;
use App\Http\Requests\UpdateMasterServiceAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $serviceAreas =MasterServiceArea::with('adminServiceAreas')->get();

        $data = ['serviceAreas' => $serviceAreas];
        dd($serviceAreas);
        return view('masters.service_areas.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceAreas = AdminServiceArea::all();
        return view('Masters.service_areas.create', compact('serviceAreas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMasterServiceAreaRequest $request)
    {

        $masterId = Auth::id();
        foreach ($request->service_area as $adminServiceAreaId) {
            \App\Models\MasterServiceArea::create([
                'master_id' => $masterId,
                'admin_service_area_id' => $adminServiceAreaId,
            ]);
        }

        return redirect()->route('masters.service_areas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterServiceArea $masterServiceArea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterServiceArea $masterServiceArea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMasterServiceAreaRequest $request, MasterServiceArea $masterServiceArea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterServiceArea $masterServiceArea)
    {
        $servicearea = MasterServiceArea::where('id', $masterServiceArea->id)->first();
        if ($servicearea) {
            $servicearea->delete();
        }
        $servicearea->delete();
        return redirect()->route('masters.service_areas.index');
    }
}
