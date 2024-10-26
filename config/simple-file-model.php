<?php

// config for MichaelBecker/SimpleFile
return [
    /*
    |--------------------------------------------------------------------------
    | User has Uuid
    |--------------------------------------------------------------------------
    |
    | This option defines whether the application uses UUIDs instead of integer
    | IDs for user model. Set to true if the User model use UUIDs.
    |
    */
    'user_has_uuid' => true,

    /*
    |--------------------------------------------------------------------------
    | Supported Image Extensions
    |--------------------------------------------------------------------------
    |
    | This array defines the file extensions that are considered as images in
    | the application. These extensions are used to identify and handle
    | image files for preview and validation purposes.
    |
    */
    'image_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
];
