@extends('admin.layouts.index', [
'pageTitle' => __('model.hoptacquocte')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'hoptacquocte',
'children'=>[
['link' => '#','name' => __('model.addhoptacquocte')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<them-hoptacquocte :danhmucs="{{$data}}"></them-hoptacquocte>
@endsection
