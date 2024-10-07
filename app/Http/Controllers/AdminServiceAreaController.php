<?php

namespace App\Http\Controllers;

use App\Models\AdminServiceArea;
use App\Http\Requests\StoreserviceareaRequest;
use App\Http\Requests\UpdateserviceareaRequest;
use Illuminate\Http\Request;

class AdminServiceAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AdminServiceArea::query();

        if ($request->filled('search')) {
            $query->where('major_area', 'like', '%' . $request->search . '%')
                ->orWhere('minor_area', 'like', '%' . $request->search . '%');
        }

        $serviceAreas = $query->paginate(10);

        return view('admins.service_areas.index', compact('serviceAreas'));
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

        AdminServiceArea::create([
            'major_area' => $request->major_area,
            'minor_area' => $request->minor_area,
            'status' => 1, // 預設狀態
        ]);

        return redirect()->route('admins.service_areas.index');
    }

    public function create()
    {
        return view('admins.service_areas.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminServiceArea $servicearea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminServiceArea $service_area)
    {
        $data = [
            'service_area'=> $service_area,
        ];
        return view('admins.service_areas.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateserviceareaRequest $request, AdminServiceArea $service_area)
    {
        $this->validate($request,[
            'major_area' => 'required|max:50',
            'minor_area' => 'required',
        ]);
        // 更新 servicearea 資料
        $service_area->major_area = $request->input('major_area');
        $service_area->minor_area = $request->input('minor_area');

        // 儲存更新後的資料
        $service_area->save();

        // 成功後重新導向並顯示訊息
        return redirect()->route('admins.service_areas.index')->with('success', '服務區域更新成功！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminServiceArea $servicearea)
    {
        $servicearea = AdminServiceArea::where('id', $servicearea->id)->first();
        if ($servicearea) {
            $servicearea->delete();
        }
        $servicearea->delete();
        return redirect()->route('admins.service_areas.index');
    }
}
