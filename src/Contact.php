<?php

namespace Bizbezzie\Zohobooks;

use Bizbezzie\Zohobooks\Facades\Zohobooks as ZohobooksAlias;

class Contact
{

    /**
     * Display a listing of the resource
     */
    public function index()
    {
        return ZohobooksAlias::get(config('zohobooks.domain') . 'contacts', 'contacts');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        string $salutation,
        string $first_name,
        string $last_name,
        string $billing_address_attention,
        string $billing_address_address,
        string $billing_address_street2,
        string $billing_address_state_code,
        string $billing_address_city,
        string $billing_address_state,
        string $billing_address_zip,
        string $billing_address_country,
        string $billing_address_fax = null,
        string $billing_address_phone = null,
        string $shipping_address_attention = null,
        string $shipping_address_address = null,
        string $shipping_address_street2 = null,
        string $shipping_address_state_code = null,
        string $shipping_address_city = null,
        string $shipping_address_state = null,
        string $shipping_address_zip = null,
        string $shipping_address_country = null,
        string $shipping_address_fax = null,
        string $shipping_address_phone = null,
        string $mobile = null,
        string $email = null,
        string $pan_no = null,
        bool   $is_customer = true,
        string $company_name = null,
        string $website = null,
        string $customer_sub_type = "individual", // "individual", "business"
        string $gst_no = null,
        string $gst_treatment = "consumer", // "business_gst" , "business_none" , "overseas" , "consumer"
        int    $credit_limit = 0,
        int    $payment_terms = 0,
        int    $opening_balance_amount = 0
    )
    {
        $data = [
            "contact_persons" => [
                [
                    "salutation"         => $salutation ?? "",
                    "first_name"         => $first_name ?? "",
                    "last_name"          => $last_name ?? "",
                    "mobile"             => $mobile ?? "",
                    "email"              => $email ?? "",
                    "is_primary_contact" => true,
                    "designation"        => "",
                    "department"         => ""
                ]
            ],

            "contact_name"      => $first_name . ' ' . $last_name,
            "company_name"      => $company_name ?? "",
            "website"           => $website ?? "",
            "contact_type"      => $is_customer ? "customer" : "vendor",
            "customer_sub_type" => $customer_sub_type,
            "pan_no"            => $pan_no,

            "billing_address"        => [
                "attention"  => $billing_address_attention,
                "address"    => $billing_address_address,
                "street2"    => $billing_address_street2,
                "state_code" => $billing_address_state_code,
                "city"       => $billing_address_city,
                "state"      => $billing_address_state,
                "zip"        => $billing_address_zip,
                "country"    => $billing_address_country,
                "fax"        => $billing_address_fax ?? "",
                "phone"      => $billing_address_phone ?? ""
            ],
            "shipping_address"       => [
                "attention"  => $shipping_address_attention ?? $billing_address_attention,
                "address"    => $shipping_address_address ?? $billing_address_address,
                "street2"    => $shipping_address_street2 ?? $billing_address_street2,
                "state_code" => $shipping_address_state_code ?? $billing_address_state_code,
                "city"       => $shipping_address_city ?? $billing_address_city,
                "state"      => $shipping_address_state ?? $billing_address_state,
                "zip"        => $shipping_address_zip ?? $billing_address_zip,
                "country"    => $shipping_address_country ?? $billing_address_country,
                "fax"        => $shipping_address_fax ?? $billing_address_fax ?? "",
                "phone"      => $shipping_address_phone ?? $billing_address_phone ?? ""
            ],
            "opening_balance_amount" => $opening_balance_amount,
            "credit_limit"           => $credit_limit,
            "payment_terms"          => $payment_terms,
            "gst_no"                 => $gst_no,
            "gst_treatment"          => $gst_treatment,
        ];

        return ZohobooksAlias::post(config('zohobooks.domain') . 'contacts', 'contact', $data);
    }

    /**
     * Display the specified resource.
     */
    public
    function show($id)
    {
        return ZohobooksAlias::get(config('zohobooks.domain') . 'contacts/' . $id, 'contact');
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
