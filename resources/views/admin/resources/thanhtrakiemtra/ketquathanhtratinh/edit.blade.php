@extends('admin.layouts.index', [
'pageTitle' => __('model.editketquathanhtratinh'),
'breadcrumbs' => [
['link' => '#', 'name' => 'Resource'],
['link' => route('ketquathanhtratinh.index'), 'name' => __('model.editketquathanhtratinh')]
]
])
@section('breadcrumb')
@breadcrumb([
'parent'=>'ketquathanhtratinh.index',
'children'=>[
['link' => '#','name' => __('model.editketquathanhtratinh')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-ketquathanhtratinh :danhmucs="{{$danhmucs}}" :ketquathanhtra="{{$ketquathanhtra}}"></edit-ketquathanhtratinh>
@endsection
