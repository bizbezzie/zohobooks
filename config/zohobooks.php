<?php
return [
    'organization_id' => env('ZOHO_ORGANIZATION_ID'),

    'client_id' => env('ZOHO_CLIENT_ID'),

    'client_secret' => env('ZOHO_CLIENT_SECRET'),

    'redirect' => env('ZOHO_REDIRECT_URI'),

    "auth_url" => env('ZOHOBOOKS_DATACENTER_BASE_API_URI', "https://accounts.zoho.in/") . "oauth/v2/auth",

    "token_url" => env('ZOHOBOOKS_DATACENTER_BASE_API_URI', "https://accounts.zoho.in/") . "oauth/v2/token",

    "domain" => "https://books.zoho" . env('ZOHOBOOKS_DATACENTER_BASE_DOMAIN', '.in') . "/api/v3/",

    /*
     * Scopes can be separated by commas
     * Default Value: "ZohoBooks.fullaccess.all"
     */
    "scope" => "ZohoBooks.fullaccess.all",
];
