<?php

namespace App\Http\Controllers;

use App\Models\AdminServiceItem;
use App\Http\Requests\StoreserviceitemRequest;
use App\Http\Requests\UpdateserviceitemRequest;
use Vinkla\Hashids\Facades\Hashids;

class AdminServiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = AdminServiceItem::all();

        $data = ['items' => $items];

        return view('admins.service_items.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.service_items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreserviceitemRequest $request)
    {
        $request->validate([
            'name'        => ['required','string'],
            'description' => ['required','string'],
            'price'       => ['required','integer','min:0','max:19999'],
        ], [
            'price.integer' => '價格必須是整數',
            'price.min'     => '價格不得小於 0',
            'price.max'     => '價格不得大於 19999',
        ]);
        $item = new AdminServiceItem;

        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->save();

        // 4. 返回設備列表，並顯示成功訊息
        return redirect()->route('admins.service_items.index')->with('success', '項目已成功新增');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminServiceItem $service_item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $hash_service_item)
    {
        $id = Hashids::decode($hash_service_item)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $service_item = AdminServiceItem ::findOrFail($id);
        $data = [
            'service_item'=> $service_item,
        ];

        return view('admins.service_items.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateserviceitemRequest $request,  $hash_service_item)
    {
        $request->validate([
            'name'        => ['required','string'],
            'description' => ['required','string'],
            'price'       => ['required','integer','min:0','max:19999'],
        ], [
            'price.integer' => '價格必須是整數',
            'price.min'     => '價格不得小於 0',
            'price.max'     => '價格不得大於 19999',
        ]);
        $id = Hashids::decode($hash_service_item)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $service_item = AdminServiceItem ::findOrFail($id);
        $service_item->name = $request->name;
        $service_item->description = $request->description;
        $service_item->price = $request->price;
        $service_item->save();

        return redirect()->route('admins.service_items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_service_item)
    {
        $id = Hashids::decode($hash_service_item)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $service_item = AdminServiceItem ::findOrFail($id);
        $service_item->delete();

        return redirect()->route('admins.service_items.index');
    }
}
