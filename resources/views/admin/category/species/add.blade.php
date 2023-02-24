@extends('admin.layouts.index', [
'pageTitle' => __('model.add_species')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'species',
'children'=>[
['link' => '#','name' => __('model.add_species')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<category-species-add></category-species-add>
@endsection
