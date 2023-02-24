@extends('admin.layouts.index', [
'pageTitle' => __('model.add_protected_area')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'protectedareas',
'children'=>[
['link' => '#','name' => __('model.add_protected_area') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<category-protectedarea-add></category-protectedarea-add>
@endsection
