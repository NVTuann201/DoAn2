@extends('admin.layouts.index', [
'pageTitle' => __('model.addcosobaoton')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'cosobaoton',
'children'=>[
['link' => '#','name' => __('model.addcosobaoton')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-cosobaoton :danhmucs="{{$danhmucs}}"></add-cosobaoton>
@endsection
