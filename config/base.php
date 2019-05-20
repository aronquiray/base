<?php

return [
    'responseBaseableName' => 'responseName',
    'models' => [
        // used for page seeder
        'page' => 'App\Models\Core\Page\Page',
    ],
    'media' => [
        'models' => [
            'metaTag' => 'App\Models\MetaTag',
        ],
        'route_names' => [
            'destroy' => 'webapi.admin.image.destroy',
//            'upload' => 'webapi.admin.image.upload',
            'update_properties' => 'webapi.admin.image.update.property',
        ],
    ]
];
