<?php

namespace BizBezzie\ZohoBooks;

use Bizbezzie\Zohobooks\Facades\Zohobooks as ZohobooksAlias;

class Invoice
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        return ZohobooksAlias::get(config('zohobooks.domain') . 'invoices', 'invoices');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        string $customer_id,
        array  $line_items,
        bool $is_queued = true
    )
    {
        $data = [
            "customer_id" => $customer_id,
            "line_items"  => $line_items,
        ];

        return $is_queued
            ? ZohobooksAlias::postQueued(config('zohobooks.domain') . 'invoices', $data)
            : ZohobooksAlias::post(config('zohobooks.domain') . 'invoices', 'invoice', $data);
    }

    /**
     * Display the specified resource.
     */
    public
    function show($id)
    {
        return ZohobooksAlias::get(config('zohobooks.domain') . 'invoices/' . $id, 'invoice');
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
