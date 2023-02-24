@extends('admin.layouts.index', [
'pageTitle' => __('model.editkhuvucdadangsinhhoccao')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'khuvucdadangsinhhoccao',
'children'=>[
['link' => '#','name' => __('model.editkhuvucdadangsinhhoccao') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-dadang-sinhhoccao :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-dadang-sinhhoccao>
@endsection
