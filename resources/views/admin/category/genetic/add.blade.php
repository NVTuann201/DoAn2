@extends('admin.layouts.index', [
'pageTitle' => __('model.addgenetic')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'genetic',
'children'=>[
['link' => '#','name' => __('model.addgenetic')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-nguongen :danhmucs="{{$danhmucs}}"></add-nguongen>
@endsection
