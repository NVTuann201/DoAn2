<?php

namespace App\Http\Controllers\Admin;

use App\Classes;
use App\Family;
use App\Genus;
use App\Http\Controllers\Controller;
use App\KingDom;
use App\Order;
use App\PhyLum;
use App\Species;
use App\SubSpecie;
use App\Traits\GetDataCache;
use App\ViewSpecies;
use Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SpeciesController extends Controller
{
    use GetDataCache;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.category.species.index');
        }
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $search_rank = $request->get('search_rank');
        $query = ViewSpecies::query();
        $groupByRank = DB::table('view_species')
            ->select('rank', 'level_rank', DB::raw('count(*) as total'))
            ->groupBy('rank', 'level_rank')
            ->orWhere('name', 'ilike', "%{$search}%")
            ->orWhere('name_vietnamese', 'ilike', "%{$search}%")
            ->orderBy('level_rank')
            ->get();
        foreach ($groupByRank as $item) {
            $item->rank_vi = '';
            if ($item->rank == 'kingdom') {
                $item->rank_vi = 'Giới';
            }
            if ($item->rank == 'phylum') {
                $item->rank_vi = 'Ngành';
            }
            if ($item->rank == 'class') {
                $item->rank_vi = 'Lớp';
            }
            if ($item->rank == 'order') {
                $item->rank_vi = 'Bộ';
            }
            if ($item->rank == 'family') {
                $item->rank_vi = 'Họ';
            }
            if ($item->rank == 'genus') {
                $item->rank_vi = 'Chi';
            }
            if ($item->rank == 'species') {
                $item->rank_vi = 'Loài';
            }
        }
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
            });
        }
        if (isset($search_rank)) {
            $query->where('rank', $search_rank);
        }
        $query->orderBy('level_rank');
        $query->orderBy('name', 'ASC');
        $species = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => __('message.success'),
            'result' => $species,
            'rank' => $groupByRank,
        ], 200, []);
    }

    public function add(Request $request)
    {
        $rank = $request->get('rank');
        $info = $request->all();
        $info['name'] = ucfirst(trim($info['name']));
        $info['status'] = strtolower(trim($info['status']));
        $validator = [];
        switch ($rank) {
            case 'kingdom':
                $classStr = KingDom::class;
                break;
            case 'phylum':
                $classStr = PhyLum::class;
                $validator = ['kingdom_id' => 'required'];
                break;
            case 'class':
                $classStr = Classes::class;
                $validator = [
                    'kingdom_id' => 'required',
                    'phylum_id' => 'required',
                ];
                break;
            case 'order':
                $classStr = Order::class;
                $validator = [
                    'kingdom_id' => 'required',
                    'phylum_id' => 'required',
                    'class_id' => 'required',
                ];
                break;
            case 'family':
                $classStr = Family::class;
                $validator = [
                    'kingdom_id' => 'required',
                    'phylum_id' => 'required',
                    'class_id' => 'required',
                    'order_id' => 'required',
                ];
                break;
            case 'genus':
                $classStr = Genus::class;
                $validator = [
                    'kingdom_id' => 'required',
                    'phylum_id' => 'required',
                    'class_id' => 'required',
                    'order_id' => 'required',
                    'family_id' => 'required',
                ];
                break;

            case 'species':
                $classStr = Species::class;
                $validator = [
                    'kingdom_id' => 'required',
                    'phylum_id' => 'required',
                    'class_id' => 'required',
                    'order_id' => 'required',
                    'family_id' => 'required',
                    'genus_id' => 'required',
                    'resource_id' => 'required',
                ];
                break;
            case 'sub_species':
                $classStr = SubSpecie::class;
                $validator = [
                    'kingdom_id' => 'required',
                    'phylum_id' => 'required',
                    'class_id' => 'required',
                    'order_id' => 'required',
                    'family_id' => 'required',
                    'genus_id' => 'required',
                    'species_id' => 'required',
                    'resource_id' => 'required',
                ];
                if (empty($info['taxon_rank'])) {
                    $info['taxon_rank'] = $rank;
                }

                break;

            default:
                throw new Exception('Not support rank: ' . $rank);
                # code...
                break;
        }
        $tableName = with(new $classStr)->getTable();
        $validator = array_merge($validator, [

            'name' => ['required', 'max:255', Rule::unique($tableName)->where(function ($query) use ($info) {
                return $query->where('resource_id', $info['resource_id']);
            })],
            'name_vietnamese' => 'max:255',
            'status' => ['required', Rule::in(['accepted', 'synonym'])],
            'synonym_id' => 'nullable|exists:' . $tableName . ',id',
        ]);
        $validator = Validator::make($info, $validator);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'errors' => $validator->messages()->all(),
            ], 200, []);
        }
        if (empty($info['created_at'])) {
            $info['created_at'] = Carbon\Carbon::now();
        }
        try {
            $classStr::query()
                ->create($info);
            $user = Auth::user();
            createLog($user->id, 'add_species', $request->ip(), $request->header('User-Agent'), 'Thêm mới loài '.$info['name']);
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (Exception $exception) {
            dd($exception);
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }
    }

    public function create()
    {
        return view('admin.category.species.add');
    }

    public function show($rank, $id)
    {
        switch ($rank) {
            case "kingdom":
                $species = KingDom::with('resource')->find($id);
                break;
            case "phylum":
                $species = PhyLum::with('resource')->find($id);
                break;
            case "class":
                $species = Classes::with('resource')->find($id);
                break;
            case "order":
                $species = Order::with('resource')->find($id);
                break;
            case "family":
                $species = Family::with('resource')->find($id);
                break;
            case "genus":
                $species = Genus::with('resource')->find($id);
                break;
            case "species":
                $species = Species::with(['resource', 'synonym'])->find($id);
                break;
            case "sub_species":
                $species = SubSpecie::with('resource')->find($id);
                break;
            default:
        }
        return view('admin.category.species.update', [
            'species' => $species,
            'rank' => $rank,
        ]);
    }

    public function update(Request $request, $rank, $id)
    {
        $info = $request->only('name', 'name_vietnamese', 'status', 'synonym_id', 'father_id', 'resource_id');
        $info['name'] = ucfirst(trim($info['name']));
        $info['status'] = strtolower($info['status']);
        switch ($rank) {
            case "kingdom":
                $kingdom = KingDom::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:kingdoms,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                DB::beginTransaction();
                try {
                    $kingdom->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            case "phylum":
                $phylum = PhyLum::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:phylums,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['kingdom_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $phylum->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            case "class":
                $class = Classes::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:classes,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['phylum_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $class->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            case "order":
                $order = Order::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:order,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['class_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $order->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            case "family":
                $family = Family::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:families,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['order_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $family->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            case "genus":
                $genus = Genus::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:genus,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['family_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $genus->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            case "species":
                $species = Species::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:species,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['genus_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $species->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
            case "sub_species":
                $sub_species = SubSpecie::query()->findOrFail($id);
                $validator = Validator::make($info, [
                    'name' => 'required|max:255',
                    'name_vietnamese' => 'max:255',
                    'father_id' => 'required',
                    'status' => ['required', Rule::in(['accepted', 'synonym'])],
                    'synonym_id' => 'nullable|exists:sub_species,id',
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => __('message.vaidator'),
                    ], 200, []);
                }

                if (empty($info['updated_at'])) {
                    $info['updated_at'] = Carbon\Carbon::now();
                }

                $info['species_id'] = $info['father_id'];
                DB::beginTransaction();
                try {
                    $sub_species->update($info);
                    DB::commit();
                    return response()->json([
                        'message' => 'message.success',
                        'result' => [],
                    ], 200, []);
                } catch (Exception $exception) {
                    DB::rollBack();
                    Log::error($exception);
                    return response()->json([
                        'message' => __('system.update_error'),
                        'result' => [],
                    ], 500, []);
                }
                break;
            default:
        }
        $user = Auth::user();
        createLog($user->id, 'update_species', $request->ip(), $request->header('User-Agent'), 'Cập nhật loài '.$info['name']);
    }

    public function delete(Request $request, $rank, $id)
    {
        if ($rank == 'kingdom') {
            $KingDom = KingDom::find($id);
            DB::beginTransaction();
            try {
                $KingDom->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'phylum') {
            $PhyLum = PhyLum::find($id);
            DB::beginTransaction();
            try {
                $PhyLum->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'class') {
            $class = Classes::find($id);
            DB::beginTransaction();
            try {
                $class->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'order') {
            $order = Order::find($id);
            DB::beginTransaction();
            try {
                $order->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'family') {
            $family = Family::find($id);
            DB::beginTransaction();
            try {
                $family->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'genus') {
            $genus = Genus::find($id);
            DB::beginTransaction();
            try {
                $genus->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'species') {
            $species = Species::find($id);
            DB::beginTransaction();
            try {
                $species->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        if ($rank == 'sub_species') {
            $sub_species = SubSpecie::find($id);
            DB::beginTransaction();
            try {
                $sub_species->delete();
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
        }
        $user = Auth::user();
        createLog($user->id, 'update_'.$rank, $request->ip(), $request->header('User-Agent'), 'Xóa '.$rank);
    }

    public function getRanks(Request $request)
    {
        $groupByRank = DB::table('view_species')
            ->select('rank', 'level_rank')
            ->groupBy('rank', 'level_rank')
            ->orderBy('level_rank')
            ->get();

        return response()->json([
            'message' => __('message.success'),
            'result' => $groupByRank,
        ], 200, []);
    }

    public function getKingdom(Request $request)
    {
        $kingdom = KingDom::query();
        $kingdom_id = $request->get('kingdom_id');
        if (isset($kingdom_id)) {
            $kingdom->where(function ($query) use ($kingdom_id) {
                $query->orWhere('id', $kingdom_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'kingdom' => $kingdom->get(),
        ], 200, []);
    }

    public function getPhylum(Request $request)
    {
        $kingdom_id = $request->get('kingdom_id');
        $phylum_id = $request->get('phylum_id');
        $phylum = phylum::query();
        if (isset($kingdom_id)) {
            $phylum->where(function ($query) use ($kingdom_id) {
                $query->orWhere('kingdom_id', $kingdom_id);
            });
        }
        if (isset($phylum_id)) {
            $phylum->where(function ($query) use ($phylum_id) {
                $query->orWhere('id', $phylum_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'Phylums' => $phylum->get(),
        ], 200, []);
    }

    public function getClass(Request $request)
    {
        $phylum_id = $request->get('phylum_id');
        $class_id = $request->get('class_id');
        $class = Classes::query();
        if (isset($phylum_id)) {
            $class->where(function ($query) use ($phylum_id) {
                $query->orWhere('phylum_id', $phylum_id);
            });
        }
        if (isset($class_id)) {
            $class->where(function ($query) use ($class_id) {
                $query->orWhere('id', $class_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'class' => $class->get(),
        ], 200, []);
    }

    public function getOrder(Request $request)
    {
        $class_id = $request->get('class_id');
        $order_id = $request->get('order_id');
        $order = order::query();
        if (isset($class_id)) {
            $order->where(function ($query) use ($class_id) {
                $query->orWhere('class_id', $class_id);
            });
        }
        if (isset($order_id)) {
            $order->where(function ($query) use ($order_id) {
                $query->orWhere('id', $order_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'order' => $order->get(),
        ], 200, []);
    }

    public function getFamily(Request $request)
    {
        $order_id = $request->get('order_id');
        $family_id = $request->get('family_id');
        $family = Family::query();
        if (isset($order_id)) {
            $family->where(function ($query) use ($order_id) {
                $query->orWhere('order_id', $order_id);
            });
        }
        if (isset($family_id)) {
            $family->where(function ($query) use ($family_id) {
                $query->orWhere('id', $family_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'family' => $family->get(),
        ], 200, []);
    }

    public function getGenus(Request $request)
    {
        $family_id = $request->get('family_id');
        $genus_id = $request->get('genus_id');
        $genus = Genus::query();
        if (isset($family_id)) {
            $genus->where(function ($query) use ($family_id) {
                $query->orWhere('family_id', $family_id);
            });
        }
        if (isset($genus_id)) {
            $genus->where(function ($query) use ($genus_id) {
                $query->orWhere('id', $genus_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'genus' => $genus->get(),
        ], 200, []);
    }

    public function getSpecies(Request $request)
    {
        $genus_id = $request->get('genus_id');
        $species_id = $request->get('species_id');
        $Species = Species::query();
        if (isset($genus_id)) {
            $Species->where(function ($query) use ($genus_id) {
                $query->orWhere('genus_id', $genus_id);
            });
        }
        if (isset($species_id)) {
            $Species->where(function ($query) use ($species_id) {
                $query->orWhere('id', $species_id);
            });
        }
        return response()->json([
            'message' => __('message.success'),
            'species' => $Species->get(),
        ], 200, []);
    }
    public function getSubSpecies(Request $request)
    {
        $species_id = $request->get('species_id');
        $SubSpecie = SubSpecie::query();
        if (isset($species_id)) {
            $SubSpecie->where('species_id', $species_id);
        }
        return response()->json([
            'message' => __('message.success'),
            'subspecies' => $SubSpecie->get(),
        ], 200, []);
    }

    public function getSynonyms(Request $request)
    {
        $rank = $request->get('rank');
        $id = $request->get('id');
        switch ($rank) {
            case 'kingdom':
                $data = KingDom::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'phylum':
                $data = PhyLum::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'class':
                $data = Classes::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'order':
                $data = Order::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'family':
                $data = Family::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'genus':
                $data = Genus::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'species':
                $data = Species::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            case 'sub_species':
                $data = SubSpecie::where('status', 'accepted')->where('id', '<>', $id)->get();
                break;
            default:
                $data = KingDom::where('status', 'accepted')->get();
                break;
        }

        return response()->json([
            'message' => __('message.success'),
            'result' => $data,
        ], 200, []);
    }

    public function uploadSpeciesRank(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'file' => 'required|file|max:32768',      // max 32MB = 32768KB,
        ]);

        if ($validator->fails()) {
            $message = "validation failed";
            $failedRules = $validator->failed();

            if (isset($failedRules['file']['required'])) {
                $message = 'Tệp không được tìm thấy';
            } else if (isset($failedRules['file']['file'])) {
                $message = 'Không hỗ trợ định dạng tệp';
            } else if (isset($failedRules['file']['max'])) {
                $message = 'Kích thước tệp quá lớn';
            }

            return response()->json([
                'message' => $message,
                'data' => [
                    $validator->errors()->all()
                ]
            ], 400);
        }

        $file = $info['file'];
        $fileName = time() . '_' . $file->getClientOriginalName();
        set_time_limit(0);
        $arrayLoi = [];
        try {
            $file->storeAs('public/imports/', $fileName);
            if (Storage::exists('public/imports/' . $fileName)) {
                \Excel::selectSheetsByIndex(0)->load(storage_path('app/public/imports/' . $fileName), function ($reader) use (&$arrayLoi) {
                    $reader->each(function ($row) use (&$arrayLoi) {
                        $info = $row->all();
                        $rank = $info['phan_loai'];
                        $data = [];
                        $data['name'] = $info['ten_khoa_hoc'];
                        $data['name_vietnamese'] = $info['ten_tieng_viet'];
                        $data['status'] = "Accepted";
                        $data['resource_id'] = 1;
                        $classStr = null;
                        $kingdom = isset($info['gioi']) &&  $info['gioi'] ? KingDom::where('name', 'ilike', $info['gioi'])->first() : null;
                        $phylum = isset($info['nganh']) && $info['nganh'] ? PhyLum::where('name', 'ilike', $info['nganh'])->first() : null;
                        $class = isset($info['lop']) && $info['lop'] ? Classes::where('name', 'ilike', $info['lop'])->first() : null;
                        $order = isset($info['bo']) && $info['bo'] ? Order::where('name', 'ilike', $info['bo'])->first() : null;
                        $family = isset($info['ho']) && $info['ho'] ? Family::where('name', 'ilike', $info['ho'])->first() : null;
                        $genus = isset($info['chi']) && $info['chi'] ? Genus::where('name', 'ilike', $info['chi'])->first() : null;
                        $validate = false;
                        switch ($rank) {
                            case 'kingdom': //gioi
                                $classStr = KingDom::class;
                                break;
                            case 'phylum': //nganh
                                $classStr = PhyLum::class;
                                $data['kingdom_id'] = $kingdom ? $kingdom->id : null;
                                break;
                            case 'class': //lop
                                $classStr = Classes::class;
                                $data['phylum_id'] = $phylum ? $phylum->id : null;
                                $data['kingdom_id'] = $phylum ? $phylum->kingdom_id : null;
                                break;
                            case 'order': //bo
                                $classStr = Order::class;
                                $data['class_id'] = $class ? $class->id : null;
                                $data['phylum_id'] = $class ? $class->phylum_id : null;
                                $data['kingdom_id'] = $data['phylum_id'] ? PhyLum::where('id', $data['phylum_id'])->first()->kingdom_id : null;
                                break;
                            case 'family': //ho
                                $classStr = Family::class;
                                $data['order_id'] = $order ? $order->id : null;
                                $data['class_id'] = $order ? $order->class_id : null;
                                $data['phylum_id'] = $data['class_id'] ? Classes::where('id', $data['class_id'])->first()->phylum_id : null;
                                $data['kingdom_id'] = $data['phylum_id'] ?  PhyLum::where('id', $data['phylum_id'])->first()->kingdom_id : null;
                                break;
                            case 'genus': //chi
                                $classStr = Genus::class;
                                $data['family_id'] = $family ? $family->id : null;
                                $data['order_id'] = $family ? $family->order_id : null;
                                $data['class_id'] = $data['order_id'] ? Order::where('id', $data['order_id'])->first()->class_id : null;
                                $data['phylum_id'] = $data['class_id'] ? Classes::where('id', $data['class_id'])->first()->phylum_id : null;
                                $data['kingdom_id'] = $data['phylum_id'] ? PhyLum::where('id', $data['phylum_id'])->first()->kingdom_id : null;
                                break;

                            case 'species':
                                $classStr = Species::class;
                                $data['genus_id'] = $genus ? $genus->id : null;
                                $data['family_id'] = $genus ? $genus->family_id : null;
                                $data['order_id'] = $data['family_id'] ? Family::where('id', $data['family_id'])->first()->order_id : null;
                                $data['class_id'] = $data['order_id'] ? Order::where('id', $data['order_id'])->first()->class_id : null;
                                $data['phylum_id'] = $data['class_id'] ? Classes::where('id', $data['class_id'])->first()->phylum_id : null;
                                $data['kingdom_id'] = $data['phylum_id'] ? PhyLum::where('id', $data['phylum_id'])->first()->kingdom_id : null;
                                break;
                        }
                        $validate = isset($data['kingdom_id']) && $data['kingdom_id'] ? true : false;
                        if ($classStr && $validate) {
                            $classStr::query()
                                ->create($data);
                        }
                    });
                });
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            }
            return response()->json([
                'message' => 'File not found'
            ], 200, []);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }
    public function downloadTemplateSpeciesRank()
    {
        if (file_exists(public_path() . '/import/upload_rank.xlsx')) {
            $excelFile = public_path() . '/import/upload_rank.xlsx';
            return response()->download($excelFile);
        } else {
            return  response()->json([
                'message' => __('system.file_not_found')
            ], 404, []);
        }
    }
}
