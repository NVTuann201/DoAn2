<?php

namespace App\Http\Controllers;

use App\Lookup;
use App\NguonGen;
use App\TriThucTruyenThong;
use Illuminate\Http\Request;

class GeneticKnowledgeSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.genetic-knowledge.index');
    }

    public function getCategories(Request $request)
    {
        $categoryName = $request->get('name', null);
        $search = $request->get('search', '');
        $data = null;
        switch ($categoryName) {
            case 'quan_huyen':
                $data = District::select('id', 'name_vietnamese as name');
                break;
            case 'nguon_gen':
                $data = NguonGen::select('id', 'ten as name');
                break;
            default:
                $data = Lookup::where('group', $categoryName)->select('id', 'name');
                break;
        }
        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            });
        }
        return ['result' => $data->limit(10)->get()];
    }

    public function getSearchData(Request $request)
    {
        $search = $request->get('search', '');
        $nhomIds = $request->get('nhom_id');
        $nguonGenIds = $request->get('nguon_gen_ids');

        $query = TriThucTruyenThong::query()->with('nhom','nguonGen');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($nhomIds) {
            $query->whereIn('nhom_id', $nhomIds);
        }

 
        if ($nguonGenIds) {
            $query->whereHas('heSinhThai',function ($query) use ($nguonGenIds){
                $query->whereIn('id',$nguonGenIds);
            });
        }

        return $query->paginate(10);
    }
}
