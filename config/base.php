<?php

return [
    'responseBaseableName' => 'responseName',
    'models' => [
        // used for page seeder
        'page' => 'App\Models\Core\Page\Page',
        // used for media
        'metaTag' => 'App\Models\MetaTag',
    ],
    // used in middleman's
    'repositories' => [
        'page' => App\Repositories\Core\Page\PageRepository::class,
        'domain' => App\Repositories\Core\Domain\DomainRepository::class,
    ],
    'media' => [
        'route_names' => [
            'destroy' => 'webapi.admin.image.destroy',
//            'upload' => 'webapi.admin.image.upload',
            'update_properties' => 'webapi.admin.image.update.property',
        ],
    ]
];
