<?php

namespace App\Http\Controllers\Admin;

use App\DarwinCoreOccurrences;
use App\DarwinCoreSimpleImage;
use App\DarwinCoreTaxon;
use App\Http\Controllers\Controller;
use App\Media;
use App\NbdsTaxonExtension;
use App\Traits\ResponseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Optix\Media\MediaUploader;

class TaxonController extends Controller
{
    use ResponseType;
    public function viewIndex()
    {
        return view('admin.category.taxon.index');
    }
    public function getTaxon(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', null);
        $per_pager = $request->get('perPage', 10);
        $query = DarwinCoreTaxon::with('kingDom', 'phylum', 'class', 'order', 'family', 'genus', 'species', 'subSpecies', 'nbdsTaxonExtensionFirst')
            ->orderBy('created_at', 'desc');
        if ($search) {
            $search = trim($search);
            $query->where(function ($query) use ($search) {
                $query->where('vernacular_name_english', 'ilike', "%{$search}%");
                $query->orWhere('vernacular_name', 'ilike', "%{$search}%");
                $query->orWhere('scientific_name', 'ilike', "%{$search}%");
            });
        }
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function showFormEdit(Request $request, $id)
    {
        $model = DarwinCoreTaxon::with([
            "datasetResource",
            "kingdom",
            "phylum",
            "class",
            "order",
            "family",
            "genus",
            "species",
            "subspecies",
            "darwinCoreOccurrences",
        ])->findOrFail($id);
        $model['taxon_extension'] = NbdsTaxonExtension::where('darwin_core_taxon_id', $id)->first();
        return view('admin.category.taxon.edit', [
            'model' => $model,
            'danhmucs' => $this->getDataForForm(),
        ]);
    }

    public function showFormAdd()
    {
        return view('admin.category.taxon.add', [
            'danhmucs' => $this->getDataForForm(),
        ]);
    }
    public function getDataForForm()
    {
        $resources = \App\Resource::all();
        return json_encode(['resources' => $resources]);
    }
    public function store(Request $request)
    {
        $info_taxon = $request->all();
        $model = DarwinCoreTaxon::create($info_taxon);
        if ($request->has('darwin_core_occurrences')) {
            $darwin_core_occurrences = json_decode($request->get('darwin_core_occurrences'), true);
            unset($darwin_core_occurrences['georeferenced_date']);
            $model->darwinCoreOccurrences()->save(new DarwinCoreOccurrences($darwin_core_occurrences));
        }
        if ($request->has('taxon_extension')) {
            $taxon_extension = json_decode($request->get('taxon_extension'), true);
            $model->nbdsTaxonExtension()->save(new NbdsTaxonExtension($taxon_extension));
        }
        $files = $request->file('files');
        $files_new_info = $request->get('files_new_info');
        if (isset($files) && count($files) > 0) {
            foreach ($files as $index => $file) {
                $info = json_decode($files_new_info[$index], true);
                $model->addAttachMedia($file, $info);
            }
        }

        return $this->responseCreated($model);
    }
    public function update(Request $request, $id)
    {
        $model = DarwinCoreTaxon::findOrFail($id);
        $model->update($request->all());

        if ($request->has('taxon_extension')) {
            $taxonExtension = NbdsTaxonExtension::where('darwin_core_taxon_id', $id)->first();
            $taxon_extension = json_decode($request->get('taxon_extension'), true);
            if (isset($taxonExtension)) {
                $taxonExtension->update($taxon_extension);
            } else {
                $model->nbdsTaxonExtension()->save(new NbdsTaxonExtension($taxon_extension));
            }
        }
        if ($request->has('darwin_core_occurrences')) {
            $darwin_core_occurrences = json_decode($request->get('darwin_core_occurrences'), true);
            $occurrences = DarwinCoreOccurrences::where('darwin_core_taxon_id', $id)->first();
            unset($darwin_core_occurrences['georeferenced_date']);

            if (isset($occurrences)) {
                $occurrences->update($darwin_core_occurrences);
            } else {
                $model->darwinCoreOccurrences()->save(new DarwinCoreOccurrences($darwin_core_occurrences));
            }
        }
        return $this->responseUpdated($model);
    }
    public function destroy(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $model = DarwinCoreTaxon::findOrFail($id);
            $model->nbdsTaxonExtension()->delete();
            $model->darwinCoreOccurrences()->delete();
            foreach ($model->darwinCoreSimpleImages as $image) {
                $media = $image->media;
                $media->each(function ($x) {
                    $x->delete();
                });
                $image->delete();
            }
            $model->delete();
            return $this->responseDeleted($model);
        });
    }
    public function updateImageForTaxon($taxon, Request $request)
    {
        $files = $request->file('files');
        $files_new_info = $request->get('files_new_info');
        if (isset($files) && count($files) > 0) {
            foreach ($files as $index => $file) {
                $info = json_decode($files_new_info[$index], true);
                $media = MediaUploader::fromFile($file)->upload();
                $taxon_image = $taxon->darwinCoreSimpleImages()->save(new DarwinCoreSimpleImage($info));
                $taxon_image->attachMedia($media);
            }
        }
        $files_delete_info = $request->get('files_delete_info');

        if (isset($files_delete_info) && count($files_delete_info) > 0) {
            foreach ($files_delete_info as $image_id) {
                $image = DarwinCoreSimpleImage::find($image_id);
                if (isset($image)) {
                    $media = $image->media;
                    $media->each(function ($x) {
                        $x->delete();
                    });
                    $image->delete();
                }
            }
        }

        $files_old_infos = $request->get('files_old_info');
        if (isset($files_old_info) && count($files_old_info) > 0) {
            foreach ($files_old_infos as $files_old_info) {
                $files_old_info = json_decode($files_old_info, true);
                $image = DarwinCoreSimpleImage::find($files_old_info['id']);
                if (isset($image)) {
                    $image->update($files_old_info);
                }
            }
        }
    }
}
