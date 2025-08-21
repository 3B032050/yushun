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
        try {
            // 驗證輸入
            $validated = $request->validate([
                'name'        => ['required','string'],
                'description' => ['required','string'],
                'price'       => ['required','integer','min:0','max:19999'],
            ], [
                'price.integer' => '價格必須是整數',
                'price.min'     => '價格不得小於 0',
                'price.max'     => '價格不得大於 19999',
                'name.required' => '名稱為必填項目',
                'description.required' => '描述為必填項目',
            ]);

            $item = new AdminServiceItem;
            $item->name = $validated['name'];
            $item->description = $validated['description'];
            $item->price = $validated['price'];
            $item->save();

            return redirect()
                ->route('admins.service_items.index')
                ->with('success', '項目已成功新增');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // 驗證失敗
            return back()->withInput()->with('validation_errors', $e->validator->errors()->all());
        } catch (\Exception $e) {
            // 其他錯誤
            return back()
                ->withInput()
                ->with('error', '新增項目時發生錯誤：' . $e->getMessage());
        }
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
    public function edit($hash_service_item)
    {
        try {
            $id = Hashids::decode($hash_service_item)[0] ?? null;
            if (!$id) {
                return redirect()->route('admins.service_items.index')
                    ->with('error', '無效的項目 ID');
            }

            $service_item = AdminServiceItem::findOrFail($id);

            return view('admins.service_items.edit', [
                'service_item' => $service_item,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('admins.service_items.index')
                ->with('error', '讀取項目資料時發生錯誤：' . $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateserviceitemRequest $request, $hash_service_item)
    {
        try {
            // 驗證輸入
            $validated = $request->validate([
                'name'        => ['required','string'],
                'description' => ['required','string'],
                'price'       => ['required','integer','min:0','max:19999'],
            ], [
                'price.integer' => '價格必須是整數',
                'price.min'     => '價格不得小於 0',
                'price.max'     => '價格不得大於 19999',
                'name.required' => '名稱為必填項目',
                'description.required' => '描述為必填項目',
            ]);

            // 解碼 ID
            $id = Hashids::decode($hash_service_item)[0] ?? null;
            if (!$id) {
                abort(404); // 無效 ID
            }

            $service_item = AdminServiceItem::findOrFail($id);
            $service_item->name = $validated['name'];
            $service_item->description = $validated['description'];
            $service_item->price = $validated['price'];
            $service_item->save();

            return redirect()
                ->route('admins.service_items.index')
                ->with('success', '項目已成功更新');

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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_service_item)
    {
        try {
            // 解碼 ID
            $id = Hashids::decode($hash_service_item)[0] ?? null;
            if (!$id) {
                abort(404); // 無效 ID
            }

            $service_item = AdminServiceItem::findOrFail($id);
            $service_item->delete();

            return redirect()
                ->route('admins.service_items.index')
                ->with('success', '項目已成功刪除');

        } catch (\Exception $e) {
            return back()
                ->with('error','系統發生錯誤，請稍後再試' . $e->getMessage());
        }
    }

}
