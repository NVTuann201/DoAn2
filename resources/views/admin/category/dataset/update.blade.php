@extends('admin.layouts.index', [
'pageTitle' => __('model.datasets')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'dataset',
'children'=>[
['link' => '#','name' => __('model.datasets')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<category-dataset-update value="{{$dataset}}"></category-dataset-update>
@endsection
