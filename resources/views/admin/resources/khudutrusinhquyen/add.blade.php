@extends('admin.layouts.index', [
'pageTitle' => __('model.addkhudutrusinhquyen')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'khudutrusinhquyen',
'children'=>[
['link' => '#','name' => __('model.addkhudutrusinhquyen')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-dutru-sinhquyen :danhmucs="{{$danhmucs}}"></add-dutru-sinhquyen>
@endsection
