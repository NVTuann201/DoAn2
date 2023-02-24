@extends('admin.layouts.index', [
'pageTitle' => __('model.add_dataset'),
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'dataset',
'children'=>[
['link' => '#','name' => __('model.add_dataset')]
]
])
@endBreadcrumb
@endsection


@section('content-detail')
<category-dataset-add></category-dataset-add>
@endsection
