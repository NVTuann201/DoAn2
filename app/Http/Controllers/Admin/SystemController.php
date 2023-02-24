<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\GetDataCache;
use App\UserLog;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    use GetDataCache;

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function companies(Request $request)
    {
        return view('system.company.index');
    }

    public function roles(Request $request)
    {
        return view('admin.system.role.index');
    }

    public function getRoles(Request $request)
    {
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $query = \App\Role::query();
        if (isset($request->count) && is_array($request->count)) {
            $query->withCount($request->count);
        }

        $role = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'message' => __('message.success'),
            'result' => $role,
        ], 200, []);
    }

    public function getRolesMenu(Request $request, $id)
    {
        $perPage = $request->get('perpage', 3);
        $page = $request->get('page', 1);
        $getrole = \App\RoleMenu::query()->with('role:id,name')->where('menu_id', $id);
        $getmenu = \App\RoleMenu::query()->with('menu:id,name')->where('role_id', $id);

        if (isset($getrole)) {
            $getrole = $getrole->paginate($perPage, ['*'], 'page', $page);
        }
        if (isset($getmenu)) {
            $getmenu = $getmenu->paginate($perPage, ['*'], 'page', $page);
        }
        return response()->json([
            'message' => __('message.success'),
            'getrole' => $getrole,
            'getmenu' => $getmenu,
        ], 200, []);
    }

    public function indexUserLog()
    {
        return view('admin.system.userlog.index');
    }

    public function getUserLog(Request $request)
    {
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $search_user = $request->get('search_user');
        $search_action = $request->get('search_action');
        $search_time = $request->get('search_time');

        $query = UserLog::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('ip_address', 'ilike', "%{$search}%");
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%");
                });
            });
        }
        if (isset($search_user)) {
            $query->where('user_id', $search_user);
        }
        if (isset($search_action)) {
            $query->where('action', $search_action);
        }

        if (isset($search_time)) {
            switch ($search_time) {
                case 'onemonth':
                    $query->where('event_time', '>=', Carbon::now()->addMonths(-1)->startOfDay());
                    break;
                case 'threemonths':
                    $query->where('event_time', '>=', Carbon::now()->addMonths(-3)->startOfDay());
                    break;
                case 'sixmonths':
                    $query->where('event_time', '>=', Carbon::now()->addMonths(-6)->startOfDay());
                    break;
                default:
                    break;
            }
        }
        $query->orderBy('event_time', 'desc')->with('user');
        $dataset = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => __('message.success'),
            'result' => $dataset,
        ], 200, []);
    }

    public function getUsers(Request $request)
    {
        $query = UserLog::query()
            ->join('users', 'users.id', '=', 'user_logs.user_id');
        $search = $request->get('search');
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('ip_address', 'ilike', "%{$search}%");
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%");
                });
            });
        }
        $query->groupBy('user_id', 'users.name');
        $query->select('user_id', DB::raw('count(user_logs.id) as count'), DB::raw('users.name as name'));
        $query->orderBy('count', 'desc');

        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function indexTools()
    {
        return view('admin.system.tools.index');
    }

    public function getAction(Request $request)
    {
        $query = UserLog::query();
        $search = $request->get('search');
        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('ip_address', 'ilike', "%{$search}%");
                $query->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%");
                });
            });
        }
        $query->groupBy('action')->select('action', DB::raw('count(id) as count'));
        $query->orderBy('count', 'desc');

        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    function public()
    {
        DB::beginTransaction();
        try {
            DB::statement('delete from darwin_core_taxon_tree');
            DB::statement('ALTER SEQUENCE darwin_core_taxon_tree_id_seq RESTART WITH 1');
            DB::statement(
                "INSERT INTO darwin_core_taxon_tree(rank, species_id,
                    name,
                    name_vietnamese ,
                    genus_id ,
                    name_genus,
                    name_vietnamese_genus,
                    family_id,
                    name_family,
                    name_vietnamese_family,
                    order_id,
                    name_order,
                    name_vietnamese_order,
                    class_id,
                    name_class,
                    name_vietnamese_class,
                    phylum_id,
                    name_phylum,
                    name_vietnamese_phylum,
                    kingdom_id,
                    name_kingdom,
                    name_vietnamese_kingdom,
                    status,
                    synonym_id,
                    number_count,
                    resource_id)
                select 'species' as rank, species.id as species_id, species.name, species.name_vietnamese, species.genus_id,
                genus.name as name_genus, genus.name_vietnamese as name_vietnamese_genus, genus.family_id,
                families.name as name_family, families.name_vietnamese as name_vietnamese_family, order_id,
                orders.name as name_order, orders.name_vietnamese as name_vietnamese_order, class_id,
                classes.name as name_class, classes.name_vietnamese as name_vietnamese_class, phylum_id,
                phylums.name as name_phylum, phylums.name_vietnamese as name_vietnamese_phylum, kingdom_id,
                kingdoms.name as name_kingdom, kingdoms.name_vietnamese as name_vietnamese_kingdom, species.status, species.synonym_id,
                CAST(1 AS INT) AS number_count,species.resource_id
                from species
                    join genus on species.genus_id = genus.id
                    join families on genus.family_id = families.id
                    join orders on families.order_id = orders.id
                    join classes on orders.class_id = classes.id
                    join phylums on classes.phylum_id = phylums.id
                    join kingdoms on phylums.kingdom_id = kingdoms.id
                union
                select 'genus' as rank, CAST(null AS INT) AS species_id, e.name, e.name_vietnamese, e.id as genus_id,
                e.name as name_genus, e.name_vietnamese as name_vietnamese_genus, e.family_id,
                families.name as name_family, families.name_vietnamese as name_vietnamese_family, order_id,
                orders.name as name_order, orders.name_vietnamese as name_vietnamese_order, class_id,
                classes.name as name_class, classes.name_vietnamese as name_vietnamese_class, phylum_id,
                phylums.name as name_phylum, phylums.name_vietnamese as name_vietnamese_phylum, kingdom_id,
                kingdoms.name as name_kingdom, kingdoms.name_vietnamese as name_vietnamese_kingdom, e.status, e.synonym_id,
                e.number_count,e.resource_id
                from
                    (select genus.resource_id,genus.name, genus.id, genus.name_vietnamese, genus.family_id, genus.status,genus.synonym_id,count(species.id) as number_count from genus left join species on genus.id = species.genus_id group by genus.id) as e
                    join families on e.family_id = families.id
                    join orders on families.order_id = orders.id
                    join classes on orders.class_id = classes.id
                    join phylums on classes.phylum_id = phylums.id
                    join kingdoms on phylums.kingdom_id = kingdoms.id
                union
                select 'family' as rank, CAST(null AS INT) AS species_id, e.name, e.name_vietnamese, CAST(null as INT) as genus_id,
                CAST(null AS varchar(100)) as name_genus, CAST(null AS varchar(255)) as name_vietnamese_genus, e.id as family_id,
                e.name as name_family, e.name_vietnamese as name_vietnamese_family, e.order_id,
                orders.name as name_order, orders.name_vietnamese as name_vietnamese_order, class_id,
                classes.name as name_class, classes.name_vietnamese as name_vietnamese_class, phylum_id,
                phylums.name as name_phylum, phylums.name_vietnamese as name_vietnamese_phylum, kingdom_id,
                kingdoms.name as name_kingdom, kingdoms.name_vietnamese as name_vietnamese_kingdom, e.status, e.synonym_id, number_count,e.resource_id
                from
                    (select families.resource_id,families.name, families.id, families.name_vietnamese, families.order_id, families.status,families.synonym_id, count(species.id) as number_count
                    from species
                    left join genus on species.genus_id = genus.id
                    left join families on genus.family_id = families.id
                    group by families.id) as e
                    join orders on e.order_id = orders.id
                    join classes on orders.class_id = classes.id
                    join phylums on classes.phylum_id = phylums.id
                    join kingdoms on phylums.kingdom_id = kingdoms.id
                union
                select 'order' as rank, CAST(null AS INT) AS species_id, e.name, e.name_vietnamese, CAST(null as INT) as genus_id,
                CAST(null AS varchar(100)) as name_genus, CAST(null AS varchar(255)) as name_vietnamese_genus, CAST(null AS INT) AS family_id,
                CAST(null AS varchar(100)) as name_family, CAST(null AS varchar(255)) as name_vietnamese_family, e.id as order_id,
                e.name as name_order, e.name_vietnamese as name_vietnamese_order, e.class_id,
                classes.name as name_class, classes.name_vietnamese as name_vietnamese_class, phylum_id,
                phylums.name as name_phylum, phylums.name_vietnamese as name_vietnamese_phylum, kingdom_id,
                kingdoms.name as name_kingdom, kingdoms.name_vietnamese as name_vietnamese_kingdom, e.status,e.synonym_id,number_count,e.resource_id
                from
                    (select orders.resource_id,orders.name, orders.id, orders.name_vietnamese, orders.class_id, orders.status,orders.synonym_id, count(species.id) as number_count
                    from species
                    left join genus on species.genus_id = genus.id
                    left join families on genus.family_id = families.id
                    left join orders on families.order_id = orders.id
                    group by orders.id) as e
                    join classes on e.class_id = classes.id
                    join phylums on classes.phylum_id = phylums.id
                    join kingdoms on phylums.kingdom_id = kingdoms.id
                union
                select 'class' as rank, CAST(null AS INT) AS species_id, e.name, e.name_vietnamese, CAST(null as INT) as genus_id,
                CAST(null AS varchar(100)) as name_genus, CAST(null AS varchar(255)) as name_vietnamese_genus, CAST(null AS INT) AS family_id,
                CAST(null AS varchar(100)) as name_family, CAST(null AS varchar(255)) as name_vietnamese_family, CAST(null AS INT) as order_id,
                CAST(null AS varchar(100)) as name_order, CAST(null AS varchar(255)) as name_vietnamese_order, e.id as class_id,
                e.name as name_class, e.name_vietnamese as name_vietnamese_class, e.phylum_id,
                phylums.name as name_phylum, phylums.name_vietnamese as name_vietnamese_phylum, kingdom_id,
                kingdoms.name as name_kingdom, kingdoms.name_vietnamese as name_vietnamese_kingdom, e.status,e.synonym_id, e.number_count,e.resource_id
                from
                    (select classes.resource_id,classes.name, classes.id, classes.name_vietnamese, classes.phylum_id, classes.status,classes.synonym_id, count(species.id) as number_count
                    from species
                    left join genus on species.genus_id = genus.id
                    left join families on genus.family_id = families.id
                    left join orders on families.order_id = orders.id
                    left join classes on orders.class_id = classes.id
                    group by classes.id) as e
                    join phylums on e.phylum_id = phylums.id
                    join kingdoms on phylums.kingdom_id = kingdoms.id
                union
                select 'phylum' as rank, CAST(null AS INT) AS species_id, e.name, e.name_vietnamese, CAST(null as INT) as genus_id,
                CAST(null AS varchar(100)) as name_genus, CAST(null AS varchar(255)) as name_vietnamese_genus, CAST(null AS INT) AS family_id,
                CAST(null AS varchar(100)) as name_family, CAST(null AS varchar(255)) as name_vietnamese_family, CAST(null AS INT) as order_id,
                CAST(null AS varchar(100)) as name_order, CAST(null AS varchar(255)) as name_vietnamese_order, CAST(null AS INT) as class_id,
                CAST(null AS varchar(100)) as name_class, CAST(null AS varchar(255)) as name_vietnamese_class, e.id as phylum_id,
                e.name as name_phylum, e.name_vietnamese as name_vietnamese_phylum, kingdom_id,
                kingdoms.name as name_kingdom, kingdoms.name_vietnamese as name_vietnamese_kingdom, e.status,e.synonym_id,number_count,e.resource_id
                from
                    (select phylums.resource_id,phylums.name, phylums.id, phylums.name_vietnamese, phylums.kingdom_id, phylums.status,phylums.synonym_id, count(species.id) as number_count
                    from species
                    left join genus on species.genus_id = genus.id
                    left join families on genus.family_id = families.id
                    left join orders on families.order_id = orders.id
                    left join classes on orders.class_id = classes.id
                    left join phylums on classes.phylum_id = phylums.id
                    group by phylums.id) as e
                    join kingdoms on e.kingdom_id = kingdoms.id
                union
                select 'kingdom' as rank, CAST(null AS INT) AS species_id, e.name, e.name_vietnamese, CAST(null as INT) as genus_id,
                CAST(null AS varchar(100)) as name_genus, CAST(null AS varchar(255)) as name_vietnamese_genus, CAST(null AS INT) AS family_id,
                CAST(null AS varchar(100)) as name_family, CAST(null AS varchar(255)) as name_vietnamese_family, CAST(null AS INT) as order_id,
                CAST(null AS varchar(100)) as name_order, CAST(null AS varchar(255)) as name_vietnamese_order, CAST(null AS INT) as class_id,
                CAST(null AS varchar(100)) as name_class, CAST(null AS varchar(255)) as name_vietnamese_class, CAST(null AS INT) as phylum_id,
                CAST(null AS varchar(100)) as name_phylum, CAST(null AS varchar(255)) as name_vietnamese_phylum, e.id as kingdom_id,
                e.name as name_kingdom, e.name_vietnamese as name_vietnamese_kingdom, e.status,e.synonym_id,e.number_count,e.resource_id
                from
                    (select kingdoms.resource_id,kingdoms.name, kingdoms.id, kingdoms.name_vietnamese, kingdoms.status,kingdoms.synonym_id, count(species.id) as number_count
                    from species
                    left join genus on species.genus_id = genus.id
                    left join families on genus.family_id = families.id
                    left join orders on families.order_id = orders.id
                    left join classes on orders.class_id = classes.id
                    left join phylums on classes.phylum_id = phylums.id
                    left join kingdoms on phylums.kingdom_id = kingdoms.id
                group by kingdoms.id) as e "
            );

            DB::commit();
            return response()->json([
                'message' => 'Thành công',
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollback();
            dd($exception);
            return response()->json([
                'message' => 'Lỗi query',
            ], 500, []);
        }
    }
    public function getRoleCoso(Request $request)
    {
        $request->validate(['type' => 'required']);
        $query = $this->getClassByType($request->get('type'), $request);
        $query->limit(10);
        return response()->json([
            'data' => $query->get(),
        ], 200, []);
    }
    public function getClassByType($type, Request $request)
    {
        $search = $request->get('search');
        switch ($type) {
            case 'khu-bao-ton':
                $query = \App\ProtectedArea::query();
                $query->selectRaw('id, orig_name as name');
                $query->where(function ($query) use ($search) {
                    $query->orWhere('name', 'ilike', "%{$search}%");
                    $query->orWhere('orig_name', 'ilike', "%{$search}%");
                });
                break;
            case 'co-so-bao-ton':
                $query = \App\CoSoBaoTon::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                        ->orWhere('chuc_nang', 'ilike', "%{$search}%")
                        ->orWhere('co_quan_chu_quan', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;
            case 'hanh-lang-da-dang-sinh-hoc':
                $query = \App\HanhLangDaDang::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;

            case 'da-dang-sinh-hoc-cao':
                $query = \App\KhuDaDangSinhHocCao::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;

            case 'vung-ngap-nuoc-quan-trong':
                $query = \App\DatNgapNuoc::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;
            case 'vung-chim-quan-trong':
                $query = \App\VungChimQuanTrong::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;

            case 'canh-quan-sinh-thai':
                $query = \App\KhuCanhQuanSinhThai::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;
            case 'khu-du-tru-sinh-quyen':
                $query = \App\KhuDuTruSinhQuyen::query();
                $query->selectRaw('id, ten as name');
                $query->where(function ($query) use ($search) {
                    $query->where('ten', 'ilike', "%{$search}%")
                        ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                        ->orWhere('mo_ta', 'ilike', "%{$search}%");
                });
                break;
            default:
                # code...
                break;
        }
        return $query;
    }
}
