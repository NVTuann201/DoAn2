@extends('admin.layouts.index', [
'pageTitle' => __('model.editketquathanhtracoso')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'ketquathanhtracoso.index',
'children'=>[
['link' => '#','name' => __('model.editketquathanhtracoso')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-ketquathanhtracoso :danhmucs="{{$danhmucs}}" :ketquathanhtracoso="{{$ketquathanhtra}}"></edit-ketquathanhtracoso>
@endsection
