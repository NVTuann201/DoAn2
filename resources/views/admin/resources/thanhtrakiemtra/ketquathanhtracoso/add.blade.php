@extends('admin.layouts.index', [
'pageTitle' => __('model.addketquathanhtracoso')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'ketquathanhtracoso.index',
'children'=>[
['link' => '#','name' => __('model.addketquathanhtracoso')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-ketquathanhtracoso :danhmucs="{{$danhmucs}}"></add-ketquathanhtracoso>
@endsection
