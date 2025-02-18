<?php

namespace App\Http\Controllers;

use App\Models\Money;
use App\Http\Requests\StoreMoneyRequest;
use App\Http\Requests\UpdateMoneyRequest;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMoneyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Money $money)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Money $money)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMoneyRequest $request, Money $money)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Money $money)
    {
        //
    }
}
