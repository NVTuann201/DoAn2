<?php

namespace App\Http\Controllers\admin;

use App\Classes;
use App\Family;
use App\Genus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\PhyLum;
use App\Species;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Return_;

class ThanhTraKiemTraController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.thanhtrakiemtra.index');
    }

    public function viewSearch()
    {
        return view('search.nolucbaoton.inspect', [
            'lang' => Session::get('locale'),
        ]);
    }
    public function importClass(Request $request)
    {

        $data = $request->get('data');
        DB::beginTransaction();
        DB::statement('TRUNCATE classes CASCADE');
        try {
            foreach ($data as $item) {
                $phyLumId = PhyLum::where('name', $item['Phylums'])->first()->id;
                DB::table('classes')->insert([
                    'id' => $item['Id'],
                    'name' => $item['Name'],
                    'name_vietnamese' => isset($item['Vietnamese']) && $item['Vietnamese'] ? $item['Vietnamese'] : null,
                    'phylum_id' => $phyLumId
                ]);
            }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function importOrder(Request $request)
    {
        $data = $request->get('data');
        DB::beginTransaction();
        DB::statement('TRUNCATE orders CASCADE');
        set_time_limit(0);
        try {
            foreach ($data as $item) {
                $refe = Classes::where('name', $item['Class'])->first();
                if (!$refe) {
                    return $item;
                }
                if (isset($item['Vietnamese']) || isset($item['Name'])) {
                    DB::table('orders')->insert([
                        'id' => $item['Id'],
                        'name' => $item['Name'],
                        'name_vietnamese' => isset($item['Vietnamese']) && $item['Vietnamese'] ? $item['Vietnamese'] : null,
                        'class_id' => $refe->id
                    ]);
                }
            }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function importFamily(Request $request)
    {
        $data = $request->get('data');
        DB::beginTransaction();
        DB::statement('TRUNCATE families CASCADE');
        set_time_limit(0);
        try {
            foreach ($data as $item) {
                $refe = Order::where('name', $item['Order'])->first();
                if (!$refe) {
                    return $item;
                }
                if (isset($item['Vietnamese']) || isset($item['Name'])) {
                    DB::table('families')->insert([
                        'id' => $item['Id'],
                        'name' => $item['Name'],
                        'name_vietnamese' => isset($item['Vietnamese']) && $item['Vietnamese'] ? $item['Vietnamese'] : null,
                        'order_id' => $refe->id
                    ]);
                }
            }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function importGenus(Request $request)
    {
        $data = $request->get('data');
        DB::beginTransaction();
        DB::statement('TRUNCATE genus CASCADE');
        set_time_limit(0);
        try {
            foreach ($data as $item) {
                $refe = Family::where('name', $item['Family'])->first();
                if (!$refe) {
                    return $item;
                }
                if (isset($item['Vietnamese']) || isset($item['Name'])) {
                    DB::table('genus')->insert([
                        'id' => $item['Id'],
                        'name' => $item['Name'],
                        'name_vietnamese' => isset($item['Vietnamese']) && $item['Vietnamese'] ? $item['Vietnamese'] : null,
                        'family_id' => $refe->id
                    ]);
                }
            }
            DB::commit();
            return 'Done';
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function importSpecies(Request $request)
    {
        $data = $request->get('data');
        DB::beginTransaction();
        set_time_limit(0);

        try {
            $accepcted = array_filter($data, function($item){
                return $item['taxonomicStatus'] == 'accepted name';
            });
            $synomys = array_filter($data, function($item){
                return $item['taxonomicStatus'] == 'synonym';
            });

            // foreach ($data as $item) {
            //     $refe = Genus::where('name','ilike', trim($item['genus']))->first();
            //     // if (!$refe) {
            //     //     return $item;
            //     // }
            //     if ($refe && (isset($item['Vietnamese']) || isset($item['Name']))) {
            //         DB::table('species')->insert([
            //             'name' => $item['scientificName'],
            //             'name_vietnamese' => isset($item['Vietnamese']) && $item['Vietnamese'] ? $item['Vietnamese'] : null,
            //             'genus_id' => $refe->id
            //         ]);
            //     }
            // }

            foreach ($synomys as $item) {
                $refe = Genus::where('name', $item['genus'])->first();
                // if (!$refe) {
                //     return $item;
                // }
                $syno = array_filter($accepcted, function($acc) use ($item){
                    return $acc['taxonID'] == $item['acceptedNameUsageID'];
                });
                $syno = $syno ?  array_slice($syno, 0, 1)[0] : null;
                $syno_id = $syno  && Species::where('name', $syno['scientificName'])->first() ? Species::where('name', $syno['scientificName'])->first()->id : null;
                if ($refe && (isset($item['Vietnamese']) || isset($item['Name'])) && $syno_id) {
                    DB::table('species')->insert([
                        'name' => $item['scientificName'],
                        'name_vietnamese' => isset($item['Vietnamese']) && $item['Vietnamese'] ? $item['Vietnamese'] : null,
                        'genus_id' => $refe->id,
                        'synonym_id' => $syno_id
                    ]);
                }
            }
            DB::commit();
            return "Done";
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
