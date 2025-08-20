<?php

namespace App\Http\Controllers;

use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Validation\ValidationException;

class AdminMasterController extends Controller
{
    public function index()
    {
        $masters = Master::where('position', '1')->get();

        $data = ['masters' => $masters];

        return view('admins.masters.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.masters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 基本清理
            $request->merge([
                'name'    => trim((string)$request->name),
                'email'   => trim(mb_strtolower((string)$request->email)),
                'mobile'  => trim((string)$request->mobile),
                'phone'   => trim((string)$request->phone),
                'address' => trim((string)$request->address),
                'line_id' => trim((string)$request->line_id),
            ]);

            // 驗證規則
            $validated = $request->validate([
                'name'    => ['required','string','max:255'],
                'email'   => ['required','email','max:255', Rule::unique('users','email')],
                'mobile'  => ['required','regex:/^09\d{8}$/', Rule::unique('users','mobile')],
                'phone'   => ['nullable','regex:/^0\d{1,2}-?\d{6,8}$/'], // 市話格式（可調整）
                'address' => ['required','string','max:255'],
                'line_id' => ['nullable','string','max:255'],
            ], [
                'name.required'     => '姓名為必填',
                'email.required'    => 'Email 為必填',
                'email.email'       => 'Email 格式不正確',
                'email.unique'      => '此 Email 已被使用',
                'mobile.required'   => '手機號碼為必填',
                'mobile.regex'      => '手機需為 09 開頭共 10 碼',
                'mobile.unique'     => '此手機號碼已被使用',
                'phone.regex'       => '電話格式不正確（例：02-12345678 或 0212345678）',
                'address.required'  => '地址為必填',
            ]);

            // 預設密碼策略（可改）：用手機號碼當初始密碼
            $password = Hash::make($validated['mobile']);

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'mobile'   => $validated['mobile'],
                'phone'    => $validated['phone'] ?? null,
                'address'  => $validated['address'],
                'line_id'  => $validated['line_id'] ?? null,
                'password' => $password,
            ]);

            // 若有開啟驗證郵件（可選）
            // $user->sendEmailVerificationNotification();

            return redirect()->route('admins.users.index')
                ->with('success', '使用者新增成功');
        }
        catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->validator);
        }
        catch (\Throwable $e) {
            // 可在此 \Log::error($e) 以利排錯
            return back()->withInput()->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Master $master)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hash_master)
    {
        $id = Hashids::decode($hash_master)[0] ?? null;

        if (!$id) {
            abort(404); // 無效 ID
        }

        $master = Master ::findOrFail($id);

        $data = [
            'master'=> $master,
        ];

        return view('admins.masters.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $hash_master)
    {
        try {
            // 解碼 hash ID
            $id = Hashids::decode($hash_master)[0] ?? null;
            if (!$id) {
                return redirect()->back()->with('error', '無效的師傅 ID');
            }

            $master = Master::findOrFail($id);

            // 驗證輸入
            $validatedData = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('masters')->ignore($master->id),
                ],
                'phone' => [
                    'nullable',
                    'regex:/^09\d{8}$/',

                Rule::unique('masters')->ignore($master->id),
                ],
            ], [
                'name.required'  => '名稱為必填項目',
                'email.required' => 'Email 為必填項目',
                'email.unique'   => '該 Email 已存在',
                'phone.regex'    => '電話格式錯誤',
                'phone.unique'   => '電話號碼已被使用',
            ]);

            // 更新資料
            $master->update([
                'name'  => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
            ]);

            return redirect()->route('admins.masters.index')
                ->with('success', '師傅資料更新成功');

        }catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->with('validation_errors', $e->validator->errors()->all());
        }
        catch (\Exception $e) {
            // 其他系統錯誤
            return redirect()->back()
                ->withInput()
                ->with('error', '系統發生錯誤，請稍後再試');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hash_master)
    {
        try {
            $id = Hashids::decode($hash_master)[0] ?? null;

            if (!$id) {
                return redirect()->back()->with('error', '無效的師傅 ID');
            }

            $master = Master::findOrFail($id);
            $master->delete();

            return redirect()->route('admins.masters.index')
                ->with('success', '師傅資料刪除成功');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', '系統發生錯誤，無法刪除');
        }
    }

}
