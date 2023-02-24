<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Media;

class MediaController extends Controller
{
    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();

        return response()->json([
            'message' => __('message.success'),
        ], 200, []);
    }
}
