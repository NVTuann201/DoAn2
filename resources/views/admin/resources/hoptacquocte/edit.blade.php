@extends('admin.layouts.index', [
'pageTitle' => __('model.hoptacquocte')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'hoptacquocte',
'children'=>[
['link' => '#','name' => __('model.edithoptacquocte')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<capnhat-hoptacquocte :danhmucs="{{$danhmucs}}" :data="{{$data}}"></capnhat-hoptacquocte>
@endsection
