@extends('admin.layouts.index', [
'pageTitle' => __('model.addkhuvucdadangsinhhoccao')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'hanhlangdadangsinhhoc',
'children'=>[
['link' => '#','name' => __('model.addkhuvucdadangsinhhoccao') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-dadang-sinhhoccao :danhmucs="{{$danhmucs}}"></add-dadang-sinhhoccao>
@endsection
