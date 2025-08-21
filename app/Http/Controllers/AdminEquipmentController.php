<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class AdminEquipmentController extends Controller
{
    //
    public function index()
    {
        $equipments = Equipment::all();

        $data = ['equipments' => $equipments];

        return view('admins.equipment.index',$data);
    }

    public function create()
    {
        return view('admins.equipment.create');
    }

    public function store(Request $request)
    {
        try {
            // 驗證輸入
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'storage_location' => 'nullable|string|max:255',
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ], [
                'name.required'             => '設備名稱為必填項目',
                'quantity.required'         => '數量為必填項目',
                'quantity.integer'          => '數量必須為整數',
                'quantity.min'              => '數量至少為 1',
                'storage_location.string'   => '儲存位置格式錯誤',
                'image_path.image'          => '檔案必須為圖片',
                'image_path.mimes'          => '圖片格式錯誤，只能上傳 jpeg, png, jpg, gif, svg',
                'image_path.max'            => '圖片大小不能超過 5MB',
            ]);

            $equipment = new Equipment;

            if ($request->hasFile('image_path')) {
                $image = $request->file('image_path');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // 存到 public/storage/equipments
                Storage::disk('equipments')->put($imageName, file_get_contents($image));

                $equipment->photo = $imageName;
            }

            $equipment->name = $validated['name'];
            $equipment->quantity = $validated['quantity'];
            $equipment->storage_location = $validated['storage_location'] ?? null;
            $equipment->save();

            return redirect()
                ->route('admins.equipment.index')
                ->with('success', '設備已成功新增');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 驗證失敗
            return back()->withInput()->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            // 其他錯誤
            return back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試' . $e->getMessage());
        }
    }



    public function edit($hash_equipment)
    {
        try {
            $id = Hashids::decode($hash_equipment)[0] ?? null;

            if (!$id) {
                return redirect()->route('admins.equipment.index')
                    ->with('error', '無效的設備 ID');
            }

            $equipment = Equipment::findOrFail($id);

            return view('admins.equipment.edit', [
                'equipment' => $equipment,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('admins.equipment.index')
                ->with('error', '讀取設備資料時發生錯誤：' . $e->getMessage());
        }
    }


    public function update(Request $request, $hash_equipment)
    {
        try {
            $id = Hashids::decode($hash_equipment)[0] ?? null;
            if (!$id) {
                abort(404, '無效的設備 ID');
            }

            $equipment = Equipment::findOrFail($id);

            // 驗證輸入
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'storage_location' => 'nullable|string|max:255',
                'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ], [
                'name.required'           => '設備名稱為必填項目',
                'quantity.required'       => '數量為必填項目',
                'quantity.integer'        => '數量必須為整數',
                'quantity.min'            => '數量至少為 1',
                'storage_location.string' => '儲存位置格式錯誤',
                'image_path.image'        => '檔案必須為圖片',
                'image_path.mimes'        => '圖片格式錯誤，只能上傳 jpeg, png, jpg, gif, svg',
                'image_path.max'          => '圖片大小不能超過 5MB',
            ]);

            // 處理圖片
            if ($request->hasFile('image_path')) {
                if ($equipment->photo) {
                    Storage::disk('equipments')->delete($equipment->photo);
                }
                $image = $request->file('image_path');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                Storage::disk('equipments')->put($imageName, file_get_contents($image));
                $equipment->photo = $imageName;
            }

            // 更新資料
            $equipment->name = $validated['name'];
            $equipment->quantity = $validated['quantity'];
            $equipment->storage_location = $validated['storage_location'] ?? null;
            $equipment->save();

            return redirect()
                ->route('admins.equipment.index')
                ->with('success', '設備資料已成功更新');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 驗證失敗
            return back()->withInput()->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            // 其他錯誤
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試' . $e->getMessage());
        }
    }

    public function destroy($hash_equipment)
    {
        try {
            $id = Hashids::decode($hash_equipment)[0] ?? null;

            if (!$id) {
                return back()->with('error', '無效的設備 ID');
            }

            $equipment = Equipment::findOrFail($id);
            $equipment->delete();

            return redirect()->route('admins.equipment.index')
                ->with('success', '設備已成功刪除');
        } catch (\Exception $e) {
            return back()->with('error', '系統發生錯誤，請稍後再試' . $e->getMessage());
        }
    }
}
