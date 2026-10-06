<?php

return [

    'company_name' => env('QUOTE_PDF_COMPANY_NAME', 'RAIN COMPANY FOR THE RETAIL SALE OF ELECTRICAL AND ELECTRONIC DEVICES'),

    'tagline' => env('QUOTE_PDF_TAGLINE', 'Smart solutions with modern systems'),

    'phone' => env('QUOTE_PDF_PHONE', '90034327'),

    'whatsapp' => env('QUOTE_PDF_WHATSAPP', '69908320'),

    'address' => env('QUOTE_PDF_ADDRESS', 'Hawally, Block 4, Tunis Street, Jumana Complex, Basement, Shop No. 13, Kuwait'),

    /**
     * Public path relative to the public/ directory (no leading slash).
     * Leave null or missing file to omit logo in PDF.
     */
    'logo_path' => env('QUOTE_PDF_LOGO_PATH'),

    'labels' => [
        'document_title' => 'Quotation',
        'date' => 'Date',
        'phone' => 'Phone',
        'customer' => 'Customer',
        'location' => 'Location',
        'no' => 'N.O',
        'code' => 'CODE',
        'item' => 'Item',
        'description' => 'ITEM DESCRIPTION',
        'qty' => 'Qty',
        'unit_price' => 'Unit Price',
        'amount' => 'Amount (KWD)',
        'total' => 'TOTAL',
        'grand_total' => 'GRAND TOTAL',
        'currency' => 'KWD',
    ],

];
