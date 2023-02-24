@extends('admin.layouts.index', [
'pageTitle' => __('model.editkhudutrusinhquyen'),
'breadcrumbs' => [
['link' => '#', 'name' => 'Resource'],
['link' => route('khudutrusinhquyen'), 'name' => __('model.editkhudutrusinhquyen')]
]
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'khudutrusinhquyen',
'children'=>[
['link' => '#','name' => __('model.editkhudutrusinhquyen')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-dutru-sinhquyen :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-dutru-sinhquyen>
@endsection