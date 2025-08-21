<?php

namespace App\Http\Controllers;

use App\Models\AdminServiceArea;
use App\Http\Requests\StoreserviceareaRequest;
use App\Http\Requests\UpdateserviceareaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        try {
            // 標準化
            $major = trim((string)$request->major_area);
            $minor = trim((string)$request->minor_area);

            $request->merge([
                'major_area' => $major,
                'minor_area' => $minor,
            ]);

            $request->validate([
                'major_area' => ['required','string'],
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

            return redirect()
                ->route('admins.service_areas.index')
                ->with('success', '新增成功');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->validator)->with('error', '請修正表單後再送出');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', '資料庫錯誤，請稍後再試');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試');
        }
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
        try {
            $id = Hashids::decode($hash_service_area)[0] ?? null;
            if (!$id) {
                return redirect()->route('admins.service_areas.index')->with('error', '無效的地區 ID');
            }

            $service_area = AdminServiceArea::findOrFail($id);

            // 標準化
            $major = trim((string)$request->major_area);
            $minor = trim((string)$request->minor_area);

            $request->merge([
                'major_area' => $major,
                'minor_area' => $minor,
            ]);

            $request->validate([
                'major_area' => ['required','string'],
                'minor_area' => [
                    'required','string',
                    Rule::unique('admin_service_areas', 'minor_area')
                        ->where(fn($q) => $q->where('major_area', $major))
                        ->ignore($service_area->id), // 忽略自己
                ],
                // 前端若用 0/1 傳值就維持 0,1；若用 egg_yolk/egg_white 請改掉這行（或同步前端）
                'area_type'  => ['required','in:0,1'],
            ], [
                'minor_area.unique' => '相同縣市下的這個鄉鎮地區已存在。',
            ]);

            $service_area->update([
                'major_area' => $major,
                'minor_area' => $minor,
                'status'     => (int) $request->area_type, // 0 或 1
            ]);

            return redirect()->route('admins.service_areas.index')->with('success', '地區更新成功');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->validator)->with('error', '請修正表單後再送出');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admins.service_areas.index')->with('error', '找不到該地區資料');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', '資料庫錯誤，請稍後再試');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_service_area)
    {
        try {
            $id = Hashids::decode($hash_service_area)[0] ?? null;
            if (!$id) {
                return redirect()->route('admins.service_areas.index')->with('error', '無效的地區 ID');
            }

            $servicearea = AdminServiceArea::findOrFail($id);
            $servicearea->delete();

            return redirect()->route('admins.service_areas.index')->with('success', '刪除成功');

        } catch (ModelNotFoundException $e) {
            return redirect()->route('admins.service_areas.index')->with('error', '找不到該地區資料');
        } catch (QueryException $e) {
            return redirect()->route('admins.service_areas.index')->with('error', '資料庫錯誤，請稍後再試');
        } catch (\Exception $e) {
            return redirect()->route('admins.service_areas.index')->with('error', '系統發生錯誤，請稍後再試');
        }
    }
}
