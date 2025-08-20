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
        // 驗證輸入
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'storage_location' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        try {
            $equipment = new Equipment;

            if ($request->hasFile('image_path')) {
                $image = $request->file('image_path');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                // 存到 public/storage/equipments
                Storage::disk('equipments')->put($imageName, file_get_contents($image));

                $equipment->photo = $imageName;
            }

            $equipment->name = $request->name;
            $equipment->quantity = $request->quantity;
            $equipment->storage_location = $request->storage_location;
            $equipment->save();

            return redirect()
                ->route('admins.equipment.index')
                ->with('success', '設備已成功新增');
        } catch (\Exception $e) {
            // 有錯誤時跳轉並顯示錯誤訊息
            return back()
                ->withInput()
                ->with('error', '新增設備時發生錯誤：' . $e->getMessage());
        }
    }


    public function edit($hash_equipment)
    {
        $id = Hashids::decode($hash_equipment)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $equipment = Equipment::findOrFail($id);
        $data = [
            'equipment'=> $equipment,
        ];

        return view('admins.equipment.edit',$data);
    }

    public function update(Request $request,$hash_equipment)
    {
        $id = Hashids::decode($hash_equipment)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $equipment = Equipment::findOrFail($id);
        if ($request->hasFile('image_path')) {

            if ($equipment->photo) {
                Storage::disk('equipments')->delete($equipment->photo);
            }
            $image = $request->file('image_path');
            $imageName = time().'.'.$image->getClientOriginalExtension();

            Storage::disk('equipments')->put($imageName, file_get_contents($image));

            $equipment->photo = $imageName;
        }

        $equipment->name = $request->name;
        $equipment->quantity = $request->quantity;
        $equipment->storage_location = $request->storage_location;
        $equipment->save();

        return redirect()->route('admins.equipment.index');
    }

    public function destroy($hash_equipment)
    {
        $id = Hashids::decode($hash_equipment)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('admins.equipment.index');
    }
}
