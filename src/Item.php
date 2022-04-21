<?php

namespace Bizbezzie\Zohobooks;

use Bizbezzie\Zohobooks\Facades\Zohobooks as ZohobooksAlias;

class Item
{

    /**
     * Display a listing of the resource
     */
    public function index($user_id)
    {
        return ZohobooksAlias::get($user_id, config('zohobooks.domain') . 'items', 'items');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        $user_id,
        string $name,
        float $rate,
        string $hsn_or_sac,
        string $sku = null,
        string $product_type = 'goods', // 'goods', 'service', 'digital_service',
        bool $is_taxable = true,
        string $tax_percentage = '18%',
        string $tax_exemption_id = null, // Mandatory, if is_taxable is false.
        string $description = null,

    )
    {

        $data = [
            "name"             => $name,
            "rate"             => $rate,
            "hsn_or_sac"       => $hsn_or_sac,
            "sku"              => $sku,
            "product_type"     => $product_type,
            "is_taxable"       => $is_taxable,
            "tax_percentage"   => $tax_percentage,
            "tax_exemption_id" => $tax_exemption_id,
            "description"      => $description,
        ];

        return ZohobooksAlias::post($user_id, config('zohobooks.domain') . 'contacts', 'contact', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id, $id)
    {
        return ZohobooksAlias::get($user_id, config('zohobooks.domain') . 'items/' . $id, 'item');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }

    public function link($user_id, $customer_id, $vendor_id)
    {

        $data = [
            'vendor_id' => $vendor_id
        ];

        return ZohobooksAlias::post($user_id, config('zohobooks.domain') . 'customers/' . $customer_id . '/link', null, $data);
    }

}
