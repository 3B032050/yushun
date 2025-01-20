<?php

namespace App\Http\Controllers;

use App\Models\AdminServiceItem;
use App\Http\Requests\StoreserviceitemRequest;
use App\Http\Requests\UpdateserviceitemRequest;

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
    public function edit(AdminServiceItem $service_item)
    {
        $data = [
            'service_item'=> $service_item,
        ];

        return view('admins.service_items.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateserviceitemRequest $request, AdminServiceItem $service_item)
    {

        $service_item->name = $request->name;
        $service_item->description = $request->description;
        $service_item->price = $request->price;
        $service_item->save();

        return redirect()->route('admins.service_items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminServiceItem $service_item)
    {
        $service_item->delete();

        return redirect()->route('admins.service_items.index');
    }
}
