<?php

namespace App\Traits;

use App\DarwinCoreSimpleImage;
use Optix\Media\MediaUploader;

trait HasSimpleImage
{
    public function images()
    {
        return $this->morphMany(DarwinCoreSimpleImage::class, 'subject');

    }
    public function addAttachMedia($file, $info)
    {
        $media = MediaUploader::fromFile($file)->upload();
        $taxon_image = $this->images()->save(new DarwinCoreSimpleImage($info));
        $taxon_image->attachMedia($media);
    }
    public function updateAttachMedia($id, $info)
    {
        $image = DarwinCoreSimpleImage::find($id);
        if (isset($image)) {
            $image->update($info);
        }
    }
    public function deleteAttachMedia($id)
    {
        $image = DarwinCoreSimpleImage::find($id);
        if (isset($image)) {
            $media = $image->media;
            $media->each(function ($x) {
                $x->delete();
            });
            $image->delete();
        }

    }
}
