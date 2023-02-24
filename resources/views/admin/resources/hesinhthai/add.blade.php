@extends('admin.layouts.index', [
'pageTitle' => __('model.addhesinhthai')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'hesinhthai',
'children'=>[
['link' => '#','name' => __('model.addhesinhthai')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-hesinhthai :danhmucs="{{$danhmucs}}"></add-hesinhthai>
@endsection
