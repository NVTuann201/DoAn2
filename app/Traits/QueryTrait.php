<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait QueryTrait
{
    /**
     * filterTrait.
     * query search for search;
     *
     * query sort,desc for sort;
     *
     * query paginate,page,perpage for paginate;
     *
     * other query for filter;
     *
     * @param Request $request
     * @param Builder $query
     * @param array $option Array containing the option params.
     *    $option = [ 'arrayFilter' => [], 'arraySort' => [], 'arraySearch' => [], 'isPagination' => false ]
     *
     *    __arrayFilter__     => (string||fieldInRequest=>fieldInTable)[] fields in the DB or (fieldInRequest get in request and fieldInTable in DB) used to filter. if null or empty it has no effect.
     *
     *    __arraySort__       => string[] fields in the DB used to sort. if null or empty it has no effect.
     *
     *    __arraySearch__     => string[] fields in the DB used to search. if null or empty it has no effect.
     *
     *    __arrayHidden__     => string[] fields in the DB hidden. if null or empty it has no effect.
     *
     *    __arrayWith__     => string[] with for relationship. if null or empty it has no effect.
     *
     *    __isPagination__    => bool it is pagination. if null or empty it has no effect.
     *
     * @return Collection
     */
    public function filterTrait(Request $request, Builder $query, $option = [])
    {
        $isPagination = false;
        $option = array_merge(['arrayFilter' => [], 'arraySort' => [], 'arraySearch' => [], 'arrayHidden' => [], 'arrayWith' => [], 'arrayFilterRelationManyToMany' => [], 'isPagination' => false, 'defaultSort' => null], $option);

        // search
        $query = $this->addQueryTrait($request, $query, $option);

        // pagination
        $isPagination = $option['isPagination'] && $request->get('paginate');
        if (!$isPagination) {
            $limit = $request->get('limit');
            if (isset($limit) && $limit > 0) {
                $query->take($limit);
            }
            $data = $query->get();
            if (count($option['arrayHidden']) > 0) {
                $data = $data->makeHidden($option['arrayHidden']);
            }
        } else {
            $page = $request->get('page', 1);
            $perPage = $request->get('perpage', 10);
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);
            if (count($option['arrayHidden']) > 0) {
                $data = $paginator->makeHidden($option['arrayHidden']);
                $paginator->data = $data;
            }
            $data = $paginator;
        }
        //hidden field

        return $data;
    }
    public function addQueryTrait(Request $request, Builder $query, $option = [])
    {
        $option = array_merge(['arrayFilter' => [], 'arraySort' => [], 'arraySearch' => [], 'arrayHidden' => [], 'arrayWith' => [], 'isPagination' => false], $option);

        // search
        $query = $this->addSearchToQuery($query, $request, $option['arraySearch']);

        // sort
        $query = $this->addSortToQuery($query, $request, $option['arraySort'], $option['defaultSort']);

        // filter
        $query = $this->addFiltersToQuery($query, $request, $option['arrayFilter']);

        // with
        $query = $this->addWithToQuery($query, $request, $option['arrayWith']);

        // relationship many to many
        $query = $this->addFilterRelationManyToMany($query, $request, $option['arrayFilterRelationManyToMany']);

        return $query;
    }
    public function querySearchDate(Builder $queryBuilder, string $column, ?string $startDate = "", ?string $endDate = "")
    {
        if (empty($startDate) && empty($endDate)) {
            return $queryBuilder;
        }

        if (!empty($startDate)) {
            $startDate = Carbon::createFromFormat(config('app.format_date'), $startDate)->startOfDay();
        }
        if (!empty($endDate)) {
            $endDate = Carbon::createFromFormat(config('app.format_date'), $endDate)->endOfDay();
        }
        if (!empty($startDate) && empty($endDate)) {
            return $queryBuilder->where($column, '>', $startDate);
        } else if (empty($startDate) && !empty($endDate)) {
            return $queryBuilder->where($column, '<', $endDate);
        } else {
            return $queryBuilder->whereBetween($column, [$startDate, $endDate]);
        }
    }
    public function querySearchDateTime(Builder $queryBuilder, string $column, ?string $startDate = "", ?string $endDate = "")
    {
        if (!empty($startDate) && !empty($endDate)) {
            $startDate = Carbon::createFromFormat(config('app.format_datetime'), $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat(config('app.format_datetime'), $endDate)->endOfDay();
            return $queryBuilder->whereBetween($column, [$startDate, $endDate]);
        }
        return $queryBuilder;
    }
    public function queryRegion($query, $region)
    {
        if (!empty($region['province_id'])) {
            $query->where('province_id', $region['province_id']);
        }
        if (!empty($region['commune_id'])) {
            $query->where('commune_id', $region['commune_id']);
        }
        if (!empty($region['district_id'])) {
            $query->where('district_id', $region['district_id']);
        }

        return $query;
    }
    /**
     * addSearchToQuery.
     * params search for search;
     *
     * @param Builder $query
     * @param Request $request
     * @param string[] $arraySearch containing fields in the DB used to search.
     * if null or empty it has no effect.
     *
     * @return Builder
     */
    public function addSearchToQuery(Builder $query, Request $request, array $arraySearch)
    {
        if (empty($arraySearch) || count($arraySearch) === 0) {
            return $query;
        }
        $search = $request->get('search');
        if (!empty($search)) {
            $query->where(function ($query) use ($arraySearch, $search) {
                foreach ($arraySearch as $col) {
                    $query->orWhere($col, 'ilike', "%$search%");
                }
            });
        }
        return $query;
    }
    /**
     * addSortToQuery.
     * query sort,desc for sort;
     *
     * @param Builder $query
     * @param Request $request
     * @param string[] $arraySort containing fields in the DB used to sort.
     * if null or empty it has no effect.
     *
     * @return Builder
     */
    public function addSortToQuery(Builder $query, Request $request, array $arraySort, $defaultSort)
    {
        if ((empty($arraySort) || count($arraySort) === 0) && empty($defaultSort)) {
            return $query;
        }
        $orderBys = $request->get('sort');
        if (!empty($orderBys)) {
            $orderBys = explode(',', $orderBys);
            foreach ($orderBys as $orderBy) {
                $descending = $orderBy[0] === '-';
                $field = $orderBy[0] === '-' ? str_replace("-", "", $orderBy) : $orderBy;
                if (in_array($field, $arraySort)) {
                    $query->orderBy($field, $descending ? 'desc' : 'asc');
                }
            }
        } else {
            if (is_callable($defaultSort)) {
                $query = $defaultSort($query);
            } else {
                if (empty($defaultSort)) {
                    $defaultSort = $arraySort[0];
                }
                $descending = $defaultSort[0] === '-';
                $field = $defaultSort[0] === '-' ? str_replace("-", "", $defaultSort) : $defaultSort;
                if (in_array($field, $arraySort)) {
                    $query->orderBy($field, $descending ? 'desc' : 'asc');
                }
            }
        }
        return $query;
    }
    /**
     * addFiltersToQuery.
     *
     * @param Builder $query
     * @param Request $request
     * @param (string||string=>string)[] $arrayFilter containing fields in the DB or (key as field get in request and value as field in DB) used to filter.
     * if null or empty it has no effect.
     *
     * @return Builder
     */
    public function addFiltersToQuery(Builder $query, Request $request, array $arrayFilter)
    {
        if (empty($arrayFilter) || count($arrayFilter) === 0) {
            return $query;
        }
        foreach ($arrayFilter as $key => $filter) {
            $type = 'exact';
            if (is_numeric($key)) {
                $fieldInRequest = $filter;
                $fieldFilter = $filter;
            } else {
                $fieldInRequest = $key;
                $fieldFilter = $filter;
                if (is_array($filter)) {
                    if (isset($filter['type']) && $filter['type'] == 'string') {
                        $type = 'like';
                    }
                    $fieldFilter = isset($filter['field']) ? $filter['field'] : $key;
                }
            }
            $value = $request->get($fieldInRequest);
            if (isset($value)) {
                if ($type == 'exact') {
                    if ($value == 'null') {
                        $query->whereNull($fieldFilter);
                    } else {
                        $query->where($fieldFilter, $value);
                    }
                }
                if ($type == 'like') {
                    $query->where($fieldFilter, 'ILIKE', '%' . $value . '%');
                }
            }
        }
        return $query;
    }

    public function addWithToQuery(Builder $query, Request $request, array $arrayWith)
    {
        if (empty($arrayWith) || count($arrayWith) === 0) {
            return $query;
        }
        $with = $request->get('with');
        if (isset($with)) {
            if (is_string($with)) {
                $with = explode(",", $with);
            }
            $array_intersect = array_intersect($arrayWith, $with);
            if (isset($array_intersect) && count($array_intersect) > 0) {
                $query->with($array_intersect);
            }
        }
        return $query;
    }
    /**
     * addFilterRelationManyToMany
     *
     * @param  mixed $query
     * @param  mixed $request
     * @param  mixed $relations (["func_relation"=> string, "key" => string ])[] ex: [["func_relation"=> users, "key" => "user_id"]]
     * @return void
     */
    public function addFilterRelationManyToMany(Builder $query, Request $request, array $relations)
    {
        if (empty($relations) || count($relations) === 0) {
            return $query;
        }
        foreach ($relations as $item) {
            $att_id = $request->get($item["key"]);
            if (!empty($att_id)) {
                $query->whereHas($item["func_relation"], function ($query) use ($att_id, $item) {
                    $query->where($item["key"], $att_id);
                });
            }
        }
        return $query;
    }
}
