<?php

namespace App\Http\Controllers\Admin;

use App\DarwinCoreSimpleImage;
use App\Http\Controllers\Controller;
use App\Traits\QueryTrait;
use App\Traits\ResponseType;
use Illuminate\Http\Request;
use Optix\Media\MediaUploader;

class DarwinCoreSimpleImageController extends Controller
{
    use ResponseType, QueryTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = DarwinCoreSimpleImage::query()->with('media');

        $data = $this->filterTrait($request, $query, [
            'isPagination' => true,
            'arrayFilter' => ['subject_id', 'subject_type'],
            'arraySort' => [],
            'arraySearch' => [],
        ]);
        return response()->json($data, 200, []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');

        $info = $request->all();

        $media = MediaUploader::fromFile($file)->upload();
        $info['identifier'] = $info['identifier'] ?? $file->getClientOriginalName();
        $darwinCoreSimpleImage = DarwinCoreSimpleImage::create($info);
        $darwinCoreSimpleImage->attachMedia($media);

        return $this->responseCreated($darwinCoreSimpleImage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DarwinCoreSimpleImage  $darwinCoreSimpleImage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info = $request->all();
        $darwinCoreSimpleImage = DarwinCoreSimpleImage::findOrFail($id);
        $darwinCoreSimpleImage->update($info);

        return $this->responseUpdated($darwinCoreSimpleImage);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DarwinCoreSimpleImage  $darwinCoreSimpleImage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $darwinCoreSimpleImage = DarwinCoreSimpleImage::findOrFail($id);

        return $this->responseDeleted($darwinCoreSimpleImage);

    }
}
