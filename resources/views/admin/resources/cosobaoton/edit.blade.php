@extends('admin.layouts.index', [
'pageTitle' => __('model.editcosobaoton')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'cosobaoton',
'children'=>[
['link' => '#','name' => __('model.editcosobaoton') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-cosobaoton :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-cosobaoton>
@endsection
