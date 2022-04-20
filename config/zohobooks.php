<?php
return [

    "auth_url" => env('ZOHOBOOKS_DATACENTER_BASE_API_URI', "https://accounts.zoho.in/") . "oauth/v2/auth",

    "token_url" => env('ZOHOBOOKS_DATACENTER_BASE_API_URI', "https://accounts.zoho.in/") . "oauth/v2/token",

    "domain" => "https://books.zoho" . env('ZOHOBOOKS_DATACENTER_BASE_DOMAIN', '.in') . "/api/v3/",

    /*
     * Redirect Route Name after first token generation
     */
    "redirect_route" => 'home',

    /*
     * Scopes can be separated by commas
     * Default Value: "ZohoBooks.fullaccess.all"
     */
    "scope" => "ZohoBooks.fullaccess.all",
];
