<?php

namespace App\Traits;

use App\Media;
use App\Mediable;
use Illuminate\Support\Facades\File;

trait AttachMedia
{
    public function addAttachMedia($name, $media)
    {
        if (!empty($media)) {
            $name->attachMedia($media);
        }
    }

    public function updateAttachMedia($name, $media, $modelName)
    {
        if (!empty($media)) {
            if (Mediable::query()->where([
                ['mediable_id', $name->id],
                ['mediable_type', $modelName],
            ])->exists()) {
                $name->detachMedia();
                $name->attachMedia($media);
            } else {
                $name->attachMedia($media);
            }
        }
    }

    public function deleteAttachMedia($name)
    {
        $getOrganizationMeida = $name->getFirstMedia();
        if (!empty($getOrganizationMeida)) {
            $getIdMedia = Mediable::query()->where('mediable_id', $name->id)->first();
            $getMedia = Media::find($getIdMedia->media_id);
            $getMedia->delete();
            File::deleteDirectory(public_path('/storage/' . $getOrganizationMeida->id));
        };
    }
}
