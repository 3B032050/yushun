<?php

namespace App\Http\Controllers;
use App\Models\AdminServiceItem;
use App\Models\Master;
use App\Models\AdminServiceArea;
use App\Models\MasterServiceArea;
use App\Http\Requests\StoreMasterServiceAreaRequest;
use App\Http\Requests\UpdateMasterServiceAreaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MasterServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $masterId = Auth::guard('master')->id();
        $serviceAreas = MasterServiceArea::where('master_id', $masterId)->get();

        if ($serviceAreas->isEmpty()) {
            return redirect()->route('masters.service_areas.create_item');
        }

        $data = ['serviceAreas' => $serviceAreas];
        return view('masters.service_areas.index', $data);
    }

    public function storeServiceSelection(Request $request)
    {
        $request->validate([
            'service_item_id' => 'required',
        ]);

        session(['service_item_id' => $request->service_item_id]);

        return redirect()->route('masters.service_areas.create');
    }

    public function create_item()
    {
        $masterId = Auth::guard('master')->id();

        $serviceItems = AdminServiceItem::whereNotIn('id', function ($query) use ($masterId) {
            $query->select('admin_service_item_id')
                ->from('master_service_areas')
                ->where('master_id', $masterId);
        })->get();

        $data = ['serviceItems' => $serviceItems];
        return view('Masters.service_areas.create_item', $data);
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
        $masterId = Auth::guard('master')->id();
        $serviceItemId = session('service_item_id');

        foreach ($request->service_area as $adminServiceAreaId) {
//            $existingRecord = MasterServiceArea::where('master_id', $masterId)
//                ->where('admin_service_area_id', $adminServiceAreaId)
//                ->exists();
//
//            if ($existingRecord) {
//                return redirect()->route('masters.service_areas.index')
//                    ->with('error', '資料已經存在');
//            }

            $masterServiceArea = MasterServiceArea::create([
                'master_id' => $masterId,
                'admin_service_area_id' => $adminServiceAreaId,
                'admin_service_item_id' => $serviceItemId,
            ]);
            $masterServiceArea->adminarea()->attach([
                $adminServiceAreaId => ['admin_service_item_id' => $serviceItemId],
            ]);
//            $masterServiceArea->adminarea()->attach($adminServiceAreaId);
//            $masterServiceArea->adminitem()->attach($serviceItemId);
        }

        session()->forget('service_item_id');

        return redirect()->route('masters.service_areas.index')
            ->with('success', '服務地區已成功新增');
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
        //dd($masterServiceArea);
        //先删除与 admin_master_area_relationship 的关系
            DB::table('admin_master_area_relationship')
                ->where('master_service_area_id', $masterServiceArea->id)
                ->delete();

            $masterServiceArea->delete();

            return redirect()->route('masters.service_areas.index')->with('success', '服務區域已成功刪除');
    }
    public function testSession()
    {
        return redirect()->route('masters.service_areas.index')->with('error', '這是測試錯誤訊息');
    }
}
