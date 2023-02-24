<?php
return [
    /*
     * The disk where files should be uploaded.
     */
    'disk' => 'media',
    /*
     * The queue used to perform image conversions.
     * Leave empty to use the default queue driver.
     */
    'queue' => null,
    /*
     * The fully qualified class name of the media model.
     */
    'model' => App\Media::class,
];
