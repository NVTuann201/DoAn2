@extends('admin.layouts.index', [
'pageTitle' => __('model.addkhucanhquansinhthai')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'khucanhquansinhthai',
'children'=>[
['link' => '#','name' => __('model.addkhucanhquansinhthai') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-canhquan-sinhthai :danhmucs="{{$danhmucs}}"></add-canhquan-sinhthai>
@endsection
