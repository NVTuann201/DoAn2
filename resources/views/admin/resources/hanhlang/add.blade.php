@extends('admin.layouts.index', [
'pageTitle' => __('model.addhanhlang')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'khuvucdadangsinhhoccao',
'children'=>[
['link' => '#','name' => __('model.addhanhlang') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-hanhlang :danhmucs="{{$danhmucs}}"></add-hanhlang>
@endsection
