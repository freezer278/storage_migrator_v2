<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'source' => [
            'driver' => 's3',
            'key' => env('S3_KEY_SOURCE'),
            'secret' => env('S3_SECRET_SOURCE'),
            'region' => env('S3_REGION_SOURCE'),
            'bucket' => env('S3_BUCKET_SOURCE'),
            'endpoint' => env('S3_BUCKET_ENDPOINT_SOURCE'),
            'root' => env('S3_BUCKET_ROOT_SOURCE', '/'),
        ],

        'destination' => [
            'driver' => 's3',
            'key' => env('S3_KEY_DESTINATION'),
            'secret' => env('S3_SECRET_DESTINATION'),
            'region' => env('S3_REGION_DESTINATION'),
            'bucket' => env('S3_BUCKET_DESTINATION'),
            'endpoint' => env('S3_BUCKET_ENDPOINT_DESTINATION'),
            'root' => env('S3_BUCKET_ROOT_DESTINATION', '/'),
            'visibility' => env('S3_VISIBILITY_DESTINATION', 'public'),
        ],

    ],

];
