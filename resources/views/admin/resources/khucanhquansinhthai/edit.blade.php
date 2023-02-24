@extends('admin.layouts.index', [
'pageTitle' => __('model.editkhucanhquansinhthai'),
'breadcrumbs' => [
['link' => '#', 'name' => 'Resource'],
['link' => route('khucanhquansinhthai'), 'name' => __('model.editkhucanhquansinhthai')]
]
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'khucanhquansinhthai',
'children'=>[
['link' => '#','name' => __('model.editkhucanhquansinhthai') ]
]
])
@endBreadcrumb
@endsection


@section('content-detail')
<edit-canhquan-sinhthai :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-canhquan-sinhthai>
@endsection
