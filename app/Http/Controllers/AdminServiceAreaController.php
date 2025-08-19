<?php

namespace App\Http\Controllers;

use App\Models\AdminServiceArea;
use App\Http\Requests\StoreserviceareaRequest;
use App\Http\Requests\UpdateserviceareaRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

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
        // 先做字串標準化（去頭尾空白）
        $major = trim((string)$request->major_area);
        $minor = trim((string)$request->minor_area);

        $request->merge([
            'major_area' => $major,
            'minor_area' => $minor,
        ]);

        $request->validate([
            'major_area' => [
                'required','string',
            ],
            'minor_area' => [
                'required','string',
                Rule::unique('admin_service_areas', 'minor_area')
                    ->where(fn($q) => $q->where('major_area', $major)),
            ],
            'area_type'  => 'required|in:egg_yolk,egg_white',
        ], [
            'minor_area.unique' => '相同縣市下的這個鄉鎮地區已存在。',
        ]);

        AdminServiceArea::create([
            'major_area' => $major,
            'minor_area' => $minor,
            'status'     => $request->area_type === 'egg_yolk' ? 1 : 0,
        ]);

        return redirect()->route('admins.service_areas.index')
            ->with('success', '新增成功');
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
    public function edit($hash_service_area)
    {
        $id = Hashids::decode($hash_service_area)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $service_area = AdminServiceArea ::findOrFail($id);
        $data = [
            'service_area'=> $service_area,
        ];
        return view('admins.service_areas.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $hash_service_area)
    {
        $id = Hashids::decode($hash_service_area)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $service_area = AdminServiceArea ::findOrFail($id);

        $request->validate([
            'major_area' => 'required|string',
            'minor_area' => 'required|string',
            'area_type'  => 'required|in:0,1',
        ]);

        $service_area->update([
            'major_area' => $request->major_area,
            'minor_area' => $request->minor_area,
            'status'     => (int)$request->area_type, // 直接存 0 或 1
        ]);

        return redirect()->route('admins.service_areas.index')->with('success', '地區更新成功');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_service_area)
    {
        $id = Hashids::decode($hash_service_area)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $servicearea = AdminServiceArea ::findOrFail($id);
        $servicearea->delete();
        return redirect()->route('admins.service_areas.index');
    }
}
