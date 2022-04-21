<?php

namespace BizBezzie\ZohoBooks;

use Bizbezzie\Zohobooks\Facades\Zohobooks as ZohobooksAlias;

class Bill
{
    /**
     * Display a listing of the resource
     */
    public function index($user_id)
    {
        return ZohobooksAlias::get($user_id, config('zohobooks.domain') . 'bills', 'bill');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        $user_id,
        string $vendor_id,
        string $bill_number,
        array $line_items,
        string $date = null,
        bool $is_queued = true
    )
    {
        $data = [
            "vendor_id" => $vendor_id,
            "bill_number"  => $bill_number,
            "line_items"  => $line_items,
            "date"  => $date ?? now()->format('Y-m-d'),
        ];

        return $is_queued
            ? ZohobooksAlias::postQueued($user_id, config('zohobooks.domain') . 'bills', $data)
            : ZohobooksAlias::post($user_id, config('zohobooks.domain') . 'bills', 'bill', $data);
    }

    /**
     * Display the specified resource.
     */
    public
    function show($user_id, $id)
    {
        return ZohobooksAlias::get($user_id, config('zohobooks.domain') . 'bills/' . $id, 'bill');
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy()
    {
        //
    }
}
