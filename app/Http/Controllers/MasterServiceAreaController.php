<?php

namespace App\Http\Controllers;
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
    public function index(Request $request)
    {
//        $masterServiceAreas = MasterServiceArea::all();
//        foreach ($masterServiceAreas as $area) {
//            echo "ID: " . $area->id . "\n"; // 顯示所有 ID
//        }

        $serviceAreas = MasterServiceArea::all();

        $data = ['serviceAreas' => $serviceAreas];
        //dd($serviceAreas);
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
        $masterId = Auth::guard('master')->id();

        foreach ($request->service_area as $adminServiceAreaId) {
            if (MasterServiceArea::where('admin_service_area_id', $adminServiceAreaId)
                ->exists()) {
                return redirect()->route('masters.service_areas.index')->with('error', '資料已經存在');
            }
            else{
                $masterServiceArea = MasterServiceArea::firstOrCreate([
                    'master_id' => $masterId,
                    'admin_service_area_id' => $adminServiceAreaId,
                ]);

            }
            // 使用 Eloquent 关系插入到关系表
            $masterServiceArea->adminarea()->attach($adminServiceAreaId);
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
