@extends('admin.layouts.index', [
'pageTitle' => __('model.edithanhlang')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'khuvucdadangsinhhoccao',
'children'=>[
['link' => '#','name' => __('model.edithanhlang') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-hanhlang :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-hanhlang>
@endsection
