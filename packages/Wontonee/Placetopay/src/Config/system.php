<?php

return [
    [
        'key'    => 'sales.paymentmethods.placetopay',
        'name'   => 'Placatopay',
        'sort'   => 6,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ], 
            [
                'name'          => 'placetopay_merchant_key',
                'title'         => 'admin::app.admin.system.placetopay-merchant-key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],	
			[
                'name'          => 'salt_key',
                'title'         => 'admin::app.admin.system.placetopay-salt-key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'    => 'placetopay-website',
                'title'   => 'admin::app.admin.system.placetopay-websitestatus',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Sandbox',
                        'value' => 'Sandbox',
                    ], [
                        'title' => 'Live',
                        'value' => 'DEFAULT',
                    ],
                ],
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];