@extends('admin.layouts.index', [
'pageTitle' => __('model.edithesinhthai')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'hesinhthai',
'children'=>[
['link' => '#','name' => __('model.edithesinhthai')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-hesinhthai :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-hesinhthai>
@endsection
