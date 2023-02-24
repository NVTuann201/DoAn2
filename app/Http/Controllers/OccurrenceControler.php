<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class OccurrenceControler extends Controller
{
    public function indexView(Request $request)
    {
        $datasetId = $request->get('dataset');
        $dataset = (object) [];
        if (!empty($datasetId)) {
            $dataset = \App\DatasetResource::query()->select('title', 'id')->find($datasetId);
        }
        return view('user.occurrence.index', [
            'lang' => Session::get('locale'),
            'dataset' => $dataset,
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');
        $limit = $request->get('limit');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $filterPublishers = $request->get('publishers');
        $filterDatasets = $request->get('datasets');
        $filterYears = $request->get('filter_years');
        $resource_ids = $request->get('resource_ids');

        $query = \App\DarwinCoreOccurrences::query();
        $query->whereHas('dataResource', function ($query) {
            $query->filterStatusPublic();
        });
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->where('scientific_name', 'ilike', "%{$search}%");
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('darwinCoreTaxon', function ($query) use ($search) {
                        $query->where('taxon_rank', 'ilike', "%{$search}%");
                        $query->orWhereHas('kingDom', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('phylum', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('class', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('order', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('family', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('genus', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('species', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                        $query->orWhereHas('subSpecies', function ($query) use ($search) {
                            $query->where(function ($query) use ($search) {
                                $query->where('name', 'ilike', "%{$search}%");
                                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                            });
                        });
                    });
                });
                $query->orWhere('references', 'ilike', "%{$search}%");
                $query->orWhere('basis_of_record', 'ilike', "%{$search}%");
                $query->orWhere('occurrence_remarks', 'ilike', "%{$search}%");
                $query->orWhere('recorded_by', 'ilike', "%{$search}%");
                $query->orWhere('individual_count', 'ilike', "%{$search}%");
                $query->orWhere('sex', 'ilike', "%{$search}%");
                $query->orWhere('reproductive_condition', 'ilike', "%{$search}%");
                $query->orWhere('life_stage', 'ilike', "%{$search}%");
                $query->orWhere('behavior', 'ilike', "%{$search}%");
                $query->orWhere('sampling_protocol', 'ilike', "%{$search}%");
                $query->orWhere('event_time', 'ilike', "%{$search}%");
                $query->orWhere('locality', 'ilike', "%{$search}%");
                $query->orWhere('verbatim_event_date', 'ilike', "%{$search}%");
                $query->orWhere('verbatim_depth', 'ilike', "%{$search}%");
                $query->orWhere('specific_epithet', 'ilike', "%{$search}%");
                $query->orWhere('scientific_name_authorship', 'ilike', "%{$search}%");

                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('dataResource', function ($query) use ($search) {
                        $query->where('title', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('region', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('province', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('district', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                        $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('protectedArea', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    });
                });
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('plot', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    });
                });
            });
        }

        foreach (["kingdom", "phylum", "class", "order", "family", "genus", "species"] as $key) {
            $keyId = $key . "_id";

            $valueSearch = $request->get($keyId);

            if (isset($valueSearch)) {
                $query->whereHas("darwinCoreTaxon", function ($query) use ($valueSearch, $keyId) {
                    $query->where($keyId, $valueSearch);
                });
            }

        }

        if (!empty($filterPublishers) && is_array($filterPublishers)) {
            $query->whereHas('dataResource', function ($query) use ($filterPublishers) {
                $query->whereIn('organization_id', $filterPublishers);
            });
        }
        if (!empty($filterDatasets) && is_array($filterDatasets)) {
            $query->whereIn('dataset_resource_id', $filterDatasets);
        }
        if (!empty($filterYears) && is_array($filterYears)) {
            foreach ($filterYears as $filter) {
                $filter = json_decode($filter);
                if (!empty($filter->option) && !empty($filter->value) && is_array($filter->value)) {
                    $value = $filter->value;
                    switch ($filter->option) {
                        case "between":
                            if (count($value) > 2) {
                                if (is_numeric($value[0])) {
                                    $query->where('year', '>=', $value[0]);
                                }
                                if (is_numeric($value[1])) {
                                    $query->where('year', '<=', $value[1]);
                                }
                            }
                            break;
                        case "is":
                            if (is_numeric($value[0])) {
                                $query->where('year', '=', $value[0]);
                            }
                            break;
                        case "before_end_of":
                            if (is_numeric($value[0])) {
                                $query->where('year', '<=', $value[0]);
                            }
                            break;
                        case "after_start_of":
                            if (is_numeric($value[0])) {
                                $query->where('year', '>=', $value[0]);
                            }
                            break;
                        default:
                    }
                }
            }
        }
        if (isset($resource_ids)) {
            $query->whereHas("darwinCoreTaxon", function ($query) use ($resource_ids) {
                $query->whereIn('resource_id', $resource_ids);
            });

        }

        $query->with([
            'darwinCoreTaxon.kingDom:name,id,name_vietnamese',
            'darwinCoreTaxon.phylum:name,id,name_vietnamese',
            'darwinCoreTaxon.class:name,id,name_vietnamese',
            'darwinCoreTaxon.order:name,id,name_vietnamese',
            'darwinCoreTaxon.family:name,id,name_vietnamese',
            'darwinCoreTaxon.genus:name,id,name_vietnamese',
            'darwinCoreTaxon.species:name,id,name_vietnamese',
            'darwinCoreTaxon.subSpecies:name,id,name_vietnamese',
            'darwinCoreTaxon:taxon_rank,kingdom_id,phylum_id,class_id,order_id,family_id,genus_id,species_id,sub_species_id,id',
            'dataResource:id,title',
            'region:id,name,name_vietnamese',
            'province:id,name,name_vietnamese',
            'district:id,name,name_vietnamese',
            'protectedArea:id,name',
            'plot:id,name',
        ]);
        $query->select(['id', 'scientific_name', 'decimal_latitude', 'decimal_longitude', 'year', 'month', 'day', 'dataset_resource_id',
            'darwin_core_taxon_id', 'references', 'basis_of_record', 'occurrence_remarks', 'recorded_by', 'individual_count',
            'sex', 'reproductive_condition', 'life_stage', 'behavior', 'sampling_protocol', 'event_date', 'event_time', 'locality',
            'verbatim_elevation', 'verbatim_depth', 'specific_epithet', 'scientific_name_authorship', 'region_id', 'province_id',
            'district_id', 'protected_area_id', 'plot_id']);
        $query->orderBy('scientific_name');
        if (!empty($limit) && is_numeric($limit)) {
            $query->limit($limit);
        }
        return response()->json([
            'message' => 'Thành công',
            'fullData' => $query->get(),
            'result' => $query->paginate($perPage, ['*'], 'page', $page),
            'user' => isset($user) ? $user->role_id : '',
        ], 200, []);
    }

    public function getTopPublisers(Request $request)
    {
        $limit = $request->get('limit', 10);
        $query = \App\Organization::query();
        $query->select('id', 'name');
        $query->has('occurrences')
            ->withCount(['occurrences' => function ($query) {
                $query->whereHas('dataResource', function ($query) {
                    $query->filterStatusPublic();
                });
            }]);
        if (!is_numeric($limit)) {
            $limit = 10;
        }
        $query->limit($limit);
        $query->orderBy('occurrences_count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getTopDatasets(Request $request)
    {
        $limit = $request->get('limit', 10);
        $query = \App\DatasetResource::query()->filterStatusPublic();
        $query->select('id', 'title');
        $query->has('darwinCoreOccurrences')
            ->withCount('darwinCoreOccurrences');
        if (!is_numeric($limit)) {
            $limit = 10;
        }
        $query->limit($limit);
        $query->orderBy('darwin_core_occurrences_count', 'desc');
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function detail(Request $request, $id)
    {
        $query = \App\DarwinCoreOccurrences::query()->with([
            'darwinCoreTaxon.kingDom:name,id,name_vietnamese',
            'darwinCoreTaxon.phylum:name,id,name_vietnamese',
            'darwinCoreTaxon.class:name,id,name_vietnamese',
            'darwinCoreTaxon.order:name,id,name_vietnamese',
            'darwinCoreTaxon.family:name,id,name_vietnamese',
            'darwinCoreTaxon.genus:name,id,name_vietnamese',
            'darwinCoreTaxon.species:name,id,name_vietnamese',
            'darwinCoreTaxon:taxon_rank,kingdom_id,phylum_id,class_id,order_id,family_id,genus_id,species_id,id',
            'dataResource:id,title,citation,geographic_description,organization_id',
            'dataResource.organization:name,id,name_vietnamese',
            'region:id,name,name_vietnamese',
            'province:id,name,name_vietnamese',
            'district:id,name,name_vietnamese',
        ]);

        return view('user.occurrence.detail', [
            'lang' => Session::get('locale'),
            'model' => $query->find($id)->toJson(),
        ]);
    }
}
