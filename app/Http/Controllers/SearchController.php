<?php

namespace App\Http\Controllers;

use App\Classes;
use App\DarwinCoreOccurrences;
use App\DarwinCoreTaxon;
use App\DarwinCoreTaxonTree;
use App\DatasetResource;
use App\District;
use App\Family;
use App\Genus;
use App\KingDom;
use App\Order;
use App\Organization;
use App\PhyLum;
use App\ProtectedArea;
use App\Province;
use App\Region;
use App\Species;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Optix\Media\MediaUploader;
use Maatwebsite\Excel\Facades\Excel;

use Session;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        return view('search.everything', [
            'lang' => Session::get('locale')
        ]);
    }

    public function searchAll(Request $request)
    {
        return view('search.everything');
    }

    public function searchEverything(Request $request)
    {
        $info = $request->get('search');
        $speciesQuery = DarwinCoreTaxonTree::query()->select(['id', 'rank', 'name', 'name_vietnamese', 'name_genus', 'name_family', 'name_order', 'name_class', 'name_phylum', 'name_kingdom', 'number_count']);
        $datatsetQuery = \App\DatasetResource::query();
        $protectedareaQuery = \App\ProtectedArea::query();
        $publisherQuery = \App\Organization::query();
        if (isset($info)) {
            $speciesQuery->where(function ($query) use ($info) {
                $query->orWhere('rank', 'ilike', "%{$info}%");
                $query->orWhere('name', 'ilike', "%{$info}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$info}%");
            });
            $datatsetQuery->where(function ($query) use ($info) {
                $query->orWhere('title', 'ilike', "%{$info}%");
            });
            $protectedareaQuery->where(function ($query) use ($info) {
                $query->orWhere('name', 'ilike', "%{$info}%");
                $query->orWhere('orig_name', 'ilike', "%{$info}%");
                $query->orWhere('desig', 'ilike', "%{$info}%");
            });
            $publisherQuery->where(function ($query) use ($info) {
                $query->orWhere('name', 'ilike', "%{$info}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$info}%");
            });
        }
        $datatsetQuery->with(['organization:name,id']);
        $datatsetQuery->withCount('darwinCoreOccurrences', 'darwinCoreTaxons', 'datasetReferences');
        return response()->json([
            'info' => $info,
            'result_species' => $speciesQuery->get()->toArray(),
            'result_datatset' => $datatsetQuery->get()->toArray(),
            'result_protectedarea' => $protectedareaQuery->get()->toArray(),
            'result_publisher' => $publisherQuery->get()->toArray(),
            'lang' => Session::get('locale'),
        ], 200, []);
    }

    public function searchSpecies(Request $request)
    {
        $search_name = $request->get('search_name');
        $searchRanks = $request->get('ranks');
        $searchStatus = $request->get('status');
        $searchDatasets = $request->get('dataset_ids');
        $searchProtectedareas = $request->get('protectedarea_ids');
        $resource_ids = $request->get('resource_ids');
        $behavior = $request->get('behavior');
        $environment = $request->get('environment');
        $diversity = $request->get('diversity');
        $distribution = $request->get('distribution');
        $endangered = $request->get('endangered');
        $source = $request->get('source');
        $sub_locs = $request->get('sub_locs');
        $region_ids = $request->get('region_ids');
        $searchRankHaNoi = $request->get('rank_hn');
        $doiTuong = $request->get('doi_tuong', null);
        $moiTruongSong = $request->get('moi_truong_song', null);
        $phanBo = $request->get('phan_bo', null);
        $isExport = $request->get('is_export', null);
        $query = DarwinCoreTaxonTree::query()->select();
        $query->where(function ($query) use ($searchRanks, $searchStatus) {
            if (isset($searchRanks)) {
                $query->whereIn('rank', $searchRanks);
            }
            if (isset($searchStatus)) {
                $query->whereIn('status', $searchStatus);
            }
        });
        if (isset($searchRankHaNoi)) {
            $lastRank = end($searchRankHaNoi);
            $rank_ids = DarwinCoreTaxon::select($lastRank . '_id')->whereNotNull($lastRank . '_id')->groupBy($lastRank . '_id')->pluck($lastRank . '_id')->toArray();
            $query->where('rank', $lastRank)->whereIn($lastRank . '_id', $rank_ids);
        }
        if (isset($endangered) && count($endangered) > 0) {
            $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($endangered) {
                foreach ($endangered as $key => $item) {
                    $query->whereNotNull($item);
                    if ($key > 0) {
                        $query->orWhereNotNull($item);
                    }
                }
            });
        }

        if (isset($searchDatasets)) {
            $query->whereHas('DarwinCoreTaxon.dataResource', function ($query) use ($searchDatasets) {
                $query->whereIn('id', $searchDatasets);
            });
        }
        if (isset($resource_ids)) {
            $query->whereIn('resource_id', $resource_ids);
        }

        if (!empty($region_ids)) {
            $query->whereHas('DarwinCoreTaxon.dataResource.datasetResourceArea.protectedArea', function ($query) use ($region_ids) {
                $query->whereIn('region_id', $region_ids);
            });
        }

        if (!empty($sub_locs)) {
            $query->whereHas('DarwinCoreTaxon.dataResource.datasetResourceArea.protectedArea', function ($query) use ($sub_locs) {
                $query->whereIn('sub_loc', $sub_locs);
            });
        }
        if (isset($environment)) {
            $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($environment) {
                $query->whereIn('habitat', $environment);
            });
        }
        if (isset($diversity)) {
            $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($diversity) {
                $query->whereIn('rich_and_rare', $diversity);
            });
        }
        // if (isset($distribution)) {
        //     $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($distribution) {
        //         $query->whereIn('id', $distribution);
        //     });
        // }
        // if (isset($endangered)) {
        //     $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($endangered) {
        //         $query->whereIn('id', $endangered);
        //     });
        // }
        if (isset($source)) {
            $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($source) {
                $query->whereIn('provenance_in_vietnam', $source);
            });
        }
        if (isset($moiTruongSong) && count($moiTruongSong) > 0) {
            $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($moiTruongSong) {
                $query->where('habitat', 'ilike', "%{$moiTruongSong[0]}%");
                foreach ($moiTruongSong as $key => $item) {
                    if ($key != 0) {
                        $query->orWhere('habitat', 'ilike', "%{$item}%");
                    }
                }
            });
        }

        if (isset($phanBo) && count($phanBo) > 0) {
            $query->whereHas('DarwinCoreTaxon.nbdsTaxonExtension', function ($query) use ($phanBo) {
                $query->where('phan_bo_ha_noi', 'ilike', "%{$phanBo[0]}%");
                foreach ($phanBo as $key => $item) {
                    if ($key != 0) {
                        $query->orWhere('phan_bo_ha_noi', 'ilike', "%{$item}%");
                    }
                }
            });
        }

        if (isset($search_name)) {
            $query->where(function ($query) use ($search_name) {
                $query->orWhere('rank', 'ilike', "%{$search_name}%");
                $query->orWhere('name', 'ilike', "%{$search_name}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$search_name}%");
                $query->orWhereHas('DarwinCoreTaxon', function ($query) use ($search_name) {
                    $query->where('taxon_rank', 'species');
                    $query->where('scientific_name', 'ilike', "%{$search_name}%");
                    //$query->where('name', 'ilike', "%{$search_name}%");
                });
            });
        }
        if (!empty($doiTuong)) {
            $dataset =  DatasetResource::where(function ($query) use ($doiTuong) {
                foreach (json_decode($doiTuong) as $key => $data) {
                    $doiTuongIDs = array_map(function ($arr) {
                        return $arr->id;
                    }, $data);
                    $loai = $key == 'khu_dang_de_xuat' ? 'khu_bao_ton' : $key;
                    $query->orWhere(function ($query) use ($loai, $doiTuongIDs) {
                        $query->where('loai_doi_tuong', $loai)->whereIn('doi_tuong_id',  $doiTuongIDs);
                    });
                }
            });
            $datasetIDs = $dataset->pluck('id')->toArray();
            if (count($datasetIDs) > 0) {
                $query->whereHas('DarwinCoreTaxon.dataResource', function ($query) use ($datasetIDs) {
                    $query->whereIn('id', $datasetIDs);
                });
            }
        }
        foreach (["kingdom", "phylum", "class", "order", "family", "genus", "species"] as $key) {
            $keyId = $key . "_id";

            $valueSearch = $request->get($keyId);

            if (isset($valueSearch)) {
                $query->where($keyId, $valueSearch);
            }
        }
        if ($isExport == 1) {
           return $this->export($query->get()->toArray()); 
        }
        $result_species = $query->orderBy('number_count', 'DESC')->paginate(10)->toArray();

        $data = [
            'result_species' => $result_species,
        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getDataSpecies()
    {
        $statuses = array('EX', 'EW', 'CR', 'EN', 'VU', 'NT', 'LC', 'DD', 'NE');
        $iucns = [];
        $sach_dos = [];
        $vn_2003_statuses = 'REVKT';

        $provenances = array('native', 'exotic', 'hybrid');
        $o_viet_nams = [];
        foreach ($provenances as $prov) {
            $o_viet_nams[] = ['ma' => $prov, 'ten' => __('nbds_lang.enum_prov_' . $prov)];
        }
        $thanh_phos = [
            [
                'ma' => 'I',
                'ten' => 'I',
            ],
            [
                'ma' => 'II',
                'ten' => 'II',
            ],
            [
                'ma' => 'III',
                'ten' => 'III',
            ],
            [
                'ma' => 'NE',
                'ten' => 'NE',
            ],
        ];

        $invasive = [
            [
                'ma' => 'strongly invasive',
                'ten' => __('nbds_lang.enum_strongly_invasive'),
            ],
            [
                'ma' => 'invasive',
                'ten' => __('nbds_lang.enum_invasive'),
            ], [
                'ma' => 'non-invasive',
                'ten' => __('nbds_lang.enum_non_invasive'),
            ],
            [
                'ma' => 'unknown',
                'ten' => __('nbds_lang.enum_unknown'),
            ],
        ];
        foreach ($statuses as $status) {
            $iucn['ma'] = $status;
            $iucn['ten'] = __('nbds_lang.enum_threat_' . $status);
            $iucns[] = $iucn;
            $sach_dos[] = $iucn;
        }
        for ($i = 0; $i < strlen($vn_2003_statuses); $i++) {
            $sachdo['ma'] = $vn_2003_statuses[$i];
            $sachdo['ten'] = __('nbds_lang.enum_threat_' . $vn_2003_statuses[$i]);
            $sach_dos[] = $sachdo;
        }
        $rank_hn = [
            [

                'rank' => 'kingdom',
                'total' => DarwinCoreTaxon::whereNotNull('kingdom_id')->whereNotNull('dataset_resource_id')->distinct()->count('kingdom_id')
            ],
            [

                'rank' => 'phylum',
                'total' => DarwinCoreTaxon::whereNotNull('phylum_id')->whereNotNull('dataset_resource_id')->distinct()->count('phylum_id')
            ],
            [

                'rank' => 'class',
                'total' => DarwinCoreTaxon::whereNotNull('class_id')->whereNotNull('dataset_resource_id')->distinct()->count('class_id')

            ],
            [

                'rank' => 'order',
                'total' => DarwinCoreTaxon::whereNotNull('order_id')->whereNotNull('dataset_resource_id')->distinct()->count('order_id')

            ],
            [

                'rank' => 'family',
                'total' => DarwinCoreTaxon::whereNotNull('family_id')->whereNotNull('dataset_resource_id')->distinct()->count('family_id')
            ],
            [

                'rank' => 'genus',
                'total' => DarwinCoreTaxon::whereNotNull('genus_id')->whereNotNull('dataset_resource_id')->distinct()->count('genus_id')
            ],
            [

                'rank' => 'species',
                'total' => DarwinCoreTaxon::whereNotNull('species_id')->whereNotNull('dataset_resource_id')->distinct()->count('species_id')
            ]
        ];
        $data = [
            'gioi' => KingDom::query()->get()->toArray(),
            'nganh' => PhyLum::query()->get()->toArray(),
            'lop' => Classes::query()->get()->toArray(),
            'bo' => Order::query()->get()->toArray(),
            'ho' => Family::query()->get()->toArray(),
            'chi' => Genus::query()->get()->toArray(),
            'iucn' => $iucns,
            'sach_dos' => $sach_dos,
            'xam_lans' => $invasive,
            'thanh_phos' => $thanh_phos,
            'o_viet_nams' => $o_viet_nams,
            'khu_bao_tons' => ProtectedArea::query()->orderBy('name')->get()->toArray(),
            'rank' => DarwinCoreTaxonTree::query()->select('rank', DB::raw('count(*) as total'))->groupBy('rank')->orderBy('total', 'DESC')->get(),
            'status' => DarwinCoreTaxonTree::query()->select('status', DB::raw('count(*) as total'))->groupBy('status')->orderBy('total', 'DESC')->get(),
            'rank_hn' => $rank_hn
        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getProtectedArea()
    {
        $query = ProtectedArea::query()->select(['wdpaid', 'desig', 'name', 'marine', 'rep_area', 'status', 'status_year', 'metadataid', 'id']);
        $data = $query->orderBy('updated_at', 'desc')->paginate(10)->toArray();
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function searchRegion(Request $request)
    {
        $keyword = $request->get('keyword');
        $search_region = $request->get('search_region');
        $search_province = $request->get('search_province');
        $search_district = $request->get('search_district');
        $query = DarwinCoreOccurrences::query()->select(['scientific_name', 'basis_of_record', 'locality', 'event_date', 'id']);
        if ($request->ajax()) {
            if (isset($keyword)) {
                $query->where(function ($query) use ($keyword) {
                    $query->orWhere('scientific_name', 'ilike', "%{$keyword}%");
                    $query->orWhere('basis_of_record', 'ilike', "%{$keyword}%");
                    $query->orWhere('locality', 'ilike', "%{$keyword}%");
                });
            }
            if (isset($search_region)) {
                $query->where('region_id', $search_region);
            }
            if (isset($search_province)) {
                $query->where('province_id', $search_province);
            }
            if (isset($search_district)) {
                $query->where('district_id', $search_district);
            }
            $data = $query->paginate(10)->toArray();
            return response()->json([
                'message' => 'Thành công',
                'result' => $data,
            ], 200, []);
        }
    }

    public function indexSpecies()
    {
        return view('search.species', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function getLangSpecies()
    {
        $data = [
            'title' => __('nbds_lang.submenu_species'),
            'scientific_name' => __('nbds_lang.darwin_core_occurrences.scientific_name'),
            'vernacular_name' => __('nbds_lang.darwin_core_occurrences.vernacular_name'),
            'dataset_name' => __('nbds_lang.darwin_core_taxons.dataset_name'),
            'heading_list_action' => __('nbds_lang.heading_list_action'),
            'label_advanced_search' => __('nbds_lang.label_advanced_search'),
            'label_adv_srch_taxon' => __('nbds_lang.label_adv_srch_taxon'),
            'kingdom' => __('nbds_lang.label_adv_srch_kingdom'),
            'phylum' => __('nbds_lang.label_adv_srch_phylum'),
            'class' => __('nbds_lang.label_adv_srch_class'),
            'order' => __('nbds_lang.label_adv_srch_order'),
            'family' => __('nbds_lang.label_adv_srch_family'),
            'genus' => __('nbds_lang.label_adv_srch_genus'),
            'family' => __('nbds_lang.label_adv_srch_family'),
            'srch_status' => __('nbds_lang.label_adv_srch_status'),
            'iucn' => __('nbds_lang.label_adv_srch_iucn'),
            'red_list' => __('nbds_lang.label_adv_srch_red_list'),
            'invasive' => __('nbds_lang.label_adv_srch_invasive'),
            'pa' => __('nbds_lang.label_adv_srch_pa'),
            'srch_provenance' => __('nbds_lang.label_adv_srch_provenance'),
            'prov_vn' => __('nbds_lang.label_adv_srch_prov_vn'),
            'prov_pa' => __('nbds_lang.label_adv_srch_prov_pa'),
            'cites' => __('nbds_lang.label_adv_srch_cites'),
            'submit_species_search' => __('nbds_lang.submit_species_search'),
            'searchs' => __('nbds_lang.search'),
            'choice' => __('nbds_lang.choice'),
            'submenu_species_desc' => __('nbds_lang.submenu_species_desc'),
            'FAQ' => __('nbds_lang.FAQ'),
            'contact' => __('nbds_lang.contact'),
            'news' => __('nbds_lang.news'),
            'privacy' => __('nbds_lang.privacy'),
            'terms_and_agreements' => __('nbds_lang.terms_and_agreements'),
            'citation' => __('nbds_lang.citation'),
            'message_species_detail' => __('nbds_lang.message_species_detail'),
        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getLangDataset()
    {
        $data = [
            'title' => __('nbds_lang.submenu_species'),
            'label_dataset_pubdate' => __('nbds_lang.label_dataset_pubdate'),
            'label_dataset_from' => __('nbds_lang.label_dataset_from'),
            'label_dataset_to' => __('nbds_lang.label_dataset_to'),
            'label_dataset_georef' => __('nbds_lang.label_dataset_georef'),
            'label_advanced_search' => __('nbds_lang.label_advanced_search'),
            'label_dataset_N' => __('nbds_lang.label_dataset_N'),
            'label_dataset_E' => __('nbds_lang.label_dataset_E'),
            'label_dataset_S' => __('nbds_lang.label_dataset_E'),
            'label_dataset_W' => __('nbds_lang.label_dataset_W'),
            'label_dataset_keyword' => __('nbds_lang.label_dataset_keyword'),
            'label_dataset_provider' => __('nbds_lang.label_dataset_provider'),
            'submit_species_search' => __('nbds_lang.submit_species_search'),
            'searchs' => __('nbds_lang.search'),
            'submenu_dataset' => __('nbds_lang.submenu_dataset'),
            'title_dataset' => __('nbds_lang.dataset_resources.title'),
            'publication_date' => __('nbds_lang.dataset_resources.publication_date'),
            'language' => __('nbds_lang.dataset_resources.language'),
            'dataset_type' => __('nbds_lang.dataset_resources.dataset_type'),
            'heading_list_action' => __('nbds_lang.heading_list_action'),
            'submenu_dataset_desc' => __('nbds_lang.submenu_dataset_desc'),
            'FAQ' => __('nbds_lang.FAQ'),
            'contact' => __('nbds_lang.contact'),
            'news' => __('nbds_lang.news'),
            'privacy' => __('nbds_lang.privacy'),
            'terms_and_agreements' => __('nbds_lang.terms_and_agreements'),
            'citation' => __('nbds_lang.citation'),
            'uuid' => __('nbds_lang.dataset_resources.uuid'),
            'begin_or_single_date' => __('nbds_lang.dataset_resources.begin_or_single_date'),
            'abstract' => __('nbds_lang.dataset_resources.abstract'),
            'additional_info' => __('nbds_lang.dataset_resources.additional_info'),
            'intellectual_rights' => __('nbds_lang.dataset_resources.intellectual_rights'),
            'distribution' => __('nbds_lang.dataset_resources.distribution'),
            'coverage' => __('nbds_lang.dataset_resources.coverage'),
            'website_url' => __('nbds_lang.dataset_resources.website_url'),
            'logo_url' => __('nbds_lang.dataset_resources.logo_url'),
            'geographic_description' => __('nbds_lang.dataset_resources.geographic_description'),

            'keyword' => __('nbds_lang.dataset_resources.keyword'),
            'keyword_thesaurus' => __('nbds_lang.dataset_resources.keyword_thesaurus'),
            'end_date' => __('nbds_lang.dataset_resources.end_date'),
            'taxonomic_coverage' => __('nbds_lang.dataset_resources.taxonomic_coverage'),
            'west_bounding_coordinate' => __('nbds_lang.dataset_resources.west_bounding_coordinate'),
            'north_bounding_coordinate' => __('nbds_lang.dataset_resources.north_bounding_coordinate'),

            'south_bounding_coordinate' => __('nbds_lang.dataset_resources.south_bounding_coordinate'),
            'original_filename' => __('nbds_lang.dataset_resources.original_filename'),
            'stored_filename' => __('nbds_lang.dataset_resources.stored_filename'),
            'series' => __('nbds_lang.dataset_resources.series'),
            'table_dataset_resources_desc' => __('nbds_lang.table_dataset_resources_desc'),
        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getDataRegion()
    {
        $region = Region::query()->get();
        return response()->json([
            'message' => 'Thành công',
            'result' => $region,
        ], 200, []);
    }

    public function getDataProvince(Request $request)
    {
        $region_id = $request->get('region_id');
        $province = Province::query()->select(['name_vietnamese', 'id']);
        if (!empty($region_id)) {
            $province->where('region_id', $region_id);
        }

        // dd($province);
        return response()->json([
            'message' => 'Thành công',
            'result' => $province->get(),
        ], 200, []);
    }

    public function getDataDistrict(Request $request)
    {
        $province_id = $request->get('province_id');
        $district = District::query()->select(['name_vietnamese', 'id'])->where('province_id', $province_id)->get()->toArray();
        return response()->json([
            'message' => 'Thành công',
            'result' => $district,
        ], 200, []);
    }

    public function getLangProtectedArea()
    {
        $data = [
            'wdpaid' => __('nbds_lang.protected_areas.wdpaid'),
            'name' => __('nbds_lang.protected_areas.name'),
            'desig' => __('nbds_lang.protected_areas.desig'),
            'marine' => __('nbds_lang.protected_areas.marine'),
            'rep_area' => __('nbds_lang.protected_areas.rep_area'),
            'status' => __('nbds_lang.protected_areas.status'),
            'status_year' => __('nbds_lang.protected_areas.status_year'),
            'metadataid' => __('nbds_lang.protected_areas.metadataid'),
            'heading_list_action' => __('nbds_lang.heading_list_action'),
            'submenu_protected_area_desc' => __('nbds_lang.submenu_protected_area_desc'),
            'FAQ' => __('nbds_lang.FAQ'),
            'contact' => __('nbds_lang.contact'),
            'news' => __('nbds_lang.news'),
            'privacy' => __('nbds_lang.privacy'),
            'terms_and_agreements' => __('nbds_lang.terms_and_agreements'),
            'citation' => __('nbds_lang.citation'),
            'wdpa_pid' => __('nbds_lang.protected_areas.wdpa_pid'),
            'orig_name' => __('nbds_lang.protected_areas.orig_name'),
            'country' => __('nbds_lang.protected_areas.country'),
            'sub_loc' => __('nbds_lang.protected_areas.sub_loc'),
            'desig_eng' => __('nbds_lang.protected_areas.desig_eng'),
            'desig_type' => __('nbds_lang.protected_areas.desig_type'),
            'rep_m_area' => __('nbds_lang.protected_areas.rep_m_area'),
            'gov_type' => __('nbds_lang.protected_areas.gov_type'),
            'mang_auth' => __('nbds_lang.protected_areas.mang_auth'),
            'int_crit' => __('nbds_lang.protected_areas.int_crit'),
            'mang_plan' => __('nbds_lang.protected_areas.mang_plan'),
            'metadataid' => __('nbds_lang.protected_areas.metadataid'),
            'longitude' => __('nbds_lang.protected_areas.longitude'),
            'latitude' => __('nbds_lang.protected_areas.latitude'),
            'legislation' => __('nbds_lang.protected_areas.legislation'),
            'description' => __('nbds_lang.protected_areas.description'),
            'iucncat' => __('nbds_lang.protected_areas.iucncat'),
        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getLangRegion()
    {
        $data = [

            'title' => __('nbds_lang.submenu_region'),
            'scientific_name' => __('nbds_lang.darwin_core_occurrences.scientific_name'),
            'locality' => __('nbds_lang.darwin_core_occurrences.locality'),
            'event_date' => __('nbds_lang.darwin_core_occurrences.event_date'),
            'label_region_region' => __('nbds_lang.label_region_region'),
            'label_region_province' => __('nbds_lang.label_region_province'),
            'label_region_district' => __('nbds_lang.label_region_district'),
            'message_region_instruction' => __('nbds_lang.message_region_instruction'),
            'searchs' => __('nbds_lang.search'),
            'label_region_name' => __('nbds_lang.label_region_name'),
            'basis_of_record' => __('nbds_lang.darwin_core_occurrences.basis_of_record'),
            'heading_list_action' => __('nbds_lang.heading_list_action'),
            'submenu_region_desc' => __('nbds_lang.submenu_region_desc'),
            'label_advanced_search' => __('nbds_lang.label_advanced_search'),
            'choice' => __('nbds_lang.choice'),
            'FAQ' => __('nbds_lang.FAQ'),
            'contact' => __('nbds_lang.contact'),
            'news' => __('nbds_lang.news'),
            'privacy' => __('nbds_lang.privacy'),
            'terms_and_agreements' => __('nbds_lang.terms_and_agreements'),
            'citation' => __('nbds_lang.citation'),
            'taxon_id' => __('nbds_lang.darwin_core_occurrences.taxon_id'),
            'recorded_by' => __('nbds_lang.darwin_core_occurrences.recorded_by'),
            'day' => __('nbds_lang.darwin_core_occurrences.day'),
            'mounth' => __('nbds_lang.darwin_core_occurrences.mounth'),
            'year' => __('nbds_lang.darwin_core_occurrences.year'),
            'decimal_latitude' => __('nbds_lang.darwin_core_occurrences.decimal_latitude'),
            'decimal_longitude' => __('nbds_lang.darwin_core_occurrences.decimal_longitude'),
            'verbatim_elevation' => __('nbds_lang.darwin_core_occurrences.verbatim_elevation'),
            'individual_count' => __('nbds_lang.darwin_core_occurrences.individual_count'),
            'sampling_protocol' => __('nbds_lang.darwin_core_occurrences.sampling_protocol'),
            'life_stage' => __('nbds_lang.darwin_core_occurrences.life_stage'),
            'reproductive_condition' => __('nbds_lang.darwin_core_occurrences.reproductive_condition'),
            'behavior' => __('nbds_lang.darwin_core_occurrences.behavior'),
            'dataset_name' => __('nbds_lang.darwin_core_occurrences.dataset_name'),
        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function getSpeciesGbif()
    {
        return view('tool.species');
    }
    public function getOccurrenceGbif()
    {
        return view('tool.occurrence');
    }
    public function showSummary()
    {
        return view('tool.summary');
    }
    public function showCategory()
    {
        return view('tool.category');
    }

    public function uploadImage(Request $request)
    {
        $file = $request->file('images');

        if (isset($file)) {
            $name = strval(time());
            $media = MediaUploader::fromFile($file)
                ->useFileName($name . '_' . $file->getClientOriginalName())
                ->useName($name)
                ->upload();
            return response()->json([
                'message' => __('message.success'),
                'result' => $media,
            ], 200, []);
        }
        return response()->json([
            'message' => __('message.success'),
            'result' => [],
        ], 200, []);
    }

    public function getNbdsApi(Request $request)
    {
        $page = $request->get('page', 0);
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $type = $request->get('type');

        $kingdom = Kingdom::query()->select(DB::raw("'KINGDOM' as rank"), 'id', 'name', 'name_vietnamese', 'notes');
        $phylum = PhyLum::query()->select(DB::raw("'PHYLUM' as rank"), 'id', 'name_vietnamese', 'notes', 'name', 'kingdom_id')->with('kingDom');
        $class = Classes::query()->select(DB::raw("'CLASS' as rank"), 'id', 'name', 'name_vietnamese', 'notes', 'phylum_id')->with('phylum.kingDom');
        $order = Order::query()->select(DB::raw("'ORDER' as rank"), 'id', 'name', 'name_vietnamese', 'notes', 'class_id')->with('class.phylum.kingDom');
        $family = Family::query()->select(DB::raw("'FAMILY' as rank"), 'id', 'name', 'name_vietnamese', 'notes', 'order_id')->with('order.class.phylum.kingDom');
        $genus = Genus::query()->select(DB::raw("'GENUS' as rank"), 'id', 'name', 'name_vietnamese', 'notes', 'family_id')->with('family.order.class.phylum.kingDom');
        $species = Species::query()->select(DB::raw("'SPECIES' as rank"), 'id', 'name', 'name_vietnamese', 'notes', 'genus_id')->with('genus.family.order.class.phylum.kingDom');

        if (!empty($type)) {
            switch ($type) {
                case 'kingdom': {
                        $query = $kingdom;
                        break;
                    }
                case 'phylum': {
                        $query = $phylum;
                        break;
                    }
                case 'class': {
                        $query = $class;
                        break;
                    }
                case 'order': {
                        $query = $order;
                        break;
                    }
                case 'family': {
                        $query = $family;
                        break;
                    }
                case 'genus': {
                        $query = $genus;
                        break;
                    }
                case 'species': {
                        $query = $species;
                        break;
                    }
            }
            if (!empty($search)) {
                $query->where('name', 'ilike', "%{$search}%");
            }
            $count = $query->count();
            $data = $query->offset($page * $perPage)->limit($perPage)->get();
        } elseif (!empty($search)) {
            $searchKingDom = $kingdom->where('name', $search);
            $searchPhyLum = $phylum->where('name', $search);
            $searchClass = $class->where('name', $search);
            $searchOrder = $order->where('name', $search);
            $searchFamily = $family->where('name', $search);
            $searchGenus = $genus->where('name', $search);
            $searchSpecies = $species->where('name', $search);

            if ($searchKingDom->exists()) {
                $query = $searchKingDom;
            }
            if ($searchPhyLum->exists()) {
                $query = $searchPhyLum;
            }
            if ($searchClass->exists()) {
                $query = $searchClass;
            }
            if ($searchOrder->exists()) {
                $query = $searchOrder;
            }
            if ($searchFamily->exists()) {
                $query = $searchFamily;
            }
            if ($searchGenus->exists()) {
                $query = $searchGenus;
            }
            if ($searchSpecies->exists()) {
                $query = $searchSpecies;
            }
            $count = $query->count();
            $data = $query->offset($page * $perPage)->limit($perPage)->get();
        } else {
            $kingdom = $kingdom->get()->toArray();
            $phylum = $phylum->get()->toArray();
            $class = $class->get()->toArray();
            $order = $order->get()->toArray();
            $family = $family->get()->toArray();
            $genus = $genus->get()->toArray();
            $species = $species->get()->toArray();

            $merged = array_merge($kingdom, $phylum, $class, $order, $family, $genus, $species);
            $count = count($merged);
            $data = array_slice($merged, $page * $perPage, $perPage);
        }

        return response()->json([
            'page' => $page,
            'perPage' => $perPage,
            'count' => $count,
            'result' => $data,
        ], 200, []);
    }
    public function searchTaxon(Request $request)
    {
        $rank = $request->get('rank', 'kingdom');
        $page = $request->get('page', 1);
        $child_id = $request->get('child_id');

        $typeChild = "";
        $isLeaf = false;
        switch ($rank) {
            case 'kingdom':
                $query = KingDom::query();
                $typeChild = 'phylum';
                break;
            case 'phylum':
                $query = PhyLum::where("kingdom_id", $child_id);
                $typeChild = 'class';
                break;
            case 'class':
                $query = Classes::where("phylum_id", $child_id);
                $typeChild = 'order';
                break;
            case 'order':
                $query = Order::where("class_id", $child_id);
                $typeChild = 'family';
                break;
            case 'family':
                $query = Family::where("order_id", $child_id);
                $typeChild = 'genus';
                break;
            case 'genus':
                $query = Genus::where("family_id", $child_id);
                $typeChild = 'species';
                break;
            case 'species':
                $query = Species::where("genus_id", $child_id);
                $isLeaf = true;
                break;

            default:
                $query = KingDom::query();
                $typeChild = 'phylum';
                # code...
                break;
        }
        $data = $query->orderBy('name', 'ASC')->paginate(10, ['*'], 'page', $page);
        return response()->json([
            'message' => 'Thành công',
            'result' => $data->map(function ($item) use ($typeChild, $isLeaf, $rank) {
                $item['isLeaf'] = $isLeaf;
                $item['filter'] = ["rank" => $typeChild, "child_id" => $item->id];
                $item['rank'] = $rank;

                $item['children'] = [];
                return $item;
            }),
            "paginate" => [
                "page" => $data->currentPage(),
                "count" => $data->count(),
                "hasMorePages" => $data->hasMorePages(),
            ]
        ], 200, []);
    }

    public function export($dataSpecies)
    {
        return Excel::create('Filename', function ($excel) use ($dataSpecies) {
            $excel->sheet('species', function ($sheet) use ($dataSpecies) {
                $data = array_merge([
                    ["Dữ liệu đa dạng sinh học"], [
                        "Tên khoa học",
                        "Tên tiếng Việt",
                        "Bậc phân loại",
                        "Giới tên tiếng Anh",
                        "Giới tên tiếng Việt",
                        "Ngành tên tiếng Anh",
                        "Ngành tên tiếng Việt",
                        "Lớp tên tiếng Anh",
                        "Lớp tên tiếng Việt",
                        "Bộ tên tiếng Anh",
                        "Bộ tên tiếng Việt",
                        "Họ tên tiếng Anh",
                        "Họ tên tiếng Việt",
                        "Chi tên tiếng Anh",
                        "Chi tên tiếng Việt",
                        "Số loài"
                    ]
                ], array_map(function ($arr) {
                    return [
                        $arr["name"],
                        $arr["name_vietnamese"],
                        $arr["rank"],
                        $arr['name_kingdom'],
                        $arr['name_vietnamese_kingdom'],
                        $arr['name_phylum'],
                        $arr['name_vietnamese_phylum'],
                        $arr['name_class'],
                        $arr['name_vietnamese_class'],
                        $arr['name_order'],
                        $arr['name_vietnamese_order'],
                        $arr['name_family'],
                        $arr['name_vietnamese_family'],
                        $arr['name_genus'],
                        $arr['name_vietnamese_genus'],
                        $arr['number_count']
                    ];
                }, $dataSpecies));
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->mergeCells('A1:P1');
                $sheet->setAutoSize(true);
                $sheet->cell('A2:P2', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('A1', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
            });
        })->export('xls');
    }
}
