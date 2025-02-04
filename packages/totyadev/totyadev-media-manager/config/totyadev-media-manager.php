<?php

return [
    "model" => [
        "folder" => \TotyaDev\TotyaDevMediaManager\Models\Folder::class,
        "media" => \TotyaDev\TotyaDevMediaManager\Models\Media::class,
    ],

    "api" => [
        "active" => false,
        "middlewares" => [
            "api",
            "auth:sanctum"
        ],
        "prefix" => "api/media-manager",
        "resources" => [
            "folders" => \TotyaDev\TotyaDevMediaManager\Http\Resources\FoldersResource::class,
            "folder" => \TotyaDev\TotyaDevMediaManager\Http\Resources\FolderResource::class,
            "media" => \TotyaDev\TotyaDevMediaManager\Http\Resources\MediaResource::class
        ]
    ],

    "user" => [
        'column_name' => 'firstname', // Change the value if your field in users table is different from "name"
    ],
];
