@extends('admin.layouts.index', [
'pageTitle' => __('model.addketquathanhtratinh')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'ketquathanhtratinh.index',
'children'=>[
['link' => '#','name' => __('model.addketquathanhtratinh')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-ketquathanhtratinh :danhmucs="{{$danhmucs}}"></add-ketquathanhtratinh>
@endsection
