<?php

namespace BizBezzie\ZohoBooks;

use Bizbezzie\Zohobooks\Facades\Zohobooks as ZohobooksAlias;

class VendorPayment
{
    /**
     * Display a listing of the resource
     */
    public function index($user_id)
    {
        return ZohobooksAlias::get($user_id, config('zohobooks.domain') . 'vendorpayments', 'vendorpayments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        $user_id,
        string $customer_id,
        string $payment_mode, // check, cash, creditcard, banktransfer, bankremittance, autotransaction, others
        float $amount,
        array $invoices,
        $account_id, // ID of the cash/bank account the payment has to be deposited.
        $reference_number, // Reference number generated for the payment.
        $description = null, // Description about the payment.
        string $date = null, // Format [yyyy-mm-dd]
        bool $is_queued = true
    )
    {
        $data = [
            "customer_id" => $customer_id,
            "payment_mode"  => $payment_mode,
            "amount"  => $amount,
            "invoices"  => $invoices,
            "account_id"  => $account_id,
            "reference_number"  => $reference_number,
            "description"  => $description,
            "date"  => $date ?? now()->format('Y-m-d'),
        ];

        return $is_queued
            ? ZohobooksAlias::postQueued($user_id, config('zohobooks.domain') . 'vendorpayments', $data)
            : ZohobooksAlias::post($user_id, config('zohobooks.domain') . 'vendorpayments', 'vendorpayment', $data);
    }

    /**
     * Display the specified resource.
     */
    public
    function show($user_id, $id)
    {
        return ZohobooksAlias::get($user_id, config('zohobooks.domain') . 'vendorpayments/' . $id, 'vendorpayment');
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
