@extends('admin.layouts.index', [
'pageTitle' => __('model.addgiayphep')
])
@section('breadcrumb')
@breadcrumb([
'parent'=>'giayphep',
'children'=>[
['link' => '#','name' => __('model.addgiayphep')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-giayphep :danhmucs="{{$danhmucs}}"></add-giayphep>
@endsection
