<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $equipment = new Equipment;

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time().'.'.$image->getClientOriginalExtension();

            // 存储原始图片
            Storage::disk('equipments')->put($imageName, file_get_contents($image));

            $equipment->photo = $imageName;
        }

        $equipment->name = $request->name;
        $equipment->quantity = $request->quantity;
        $equipment->save();

        // 4. 返回設備列表，並顯示成功訊息
        return redirect()->route('admins.equipment.index')->with('success', '設備已成功新增');
    }

    public function edit(Equipment $equipment)
    {
        $data = [
            'equipment'=> $equipment,
        ];

        return view('admins.equipment.edit',$data);
    }

    public function update(Request $request,Equipment $equipment)
    {

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
        $equipment->save();

        return redirect()->route('admins.equipment.index');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()->route('admins.equipment.index');
    }
}
