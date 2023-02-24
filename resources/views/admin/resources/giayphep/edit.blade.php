@extends('admin.layouts.index', [
'pageTitle' => __('model.editgiayphep')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'giayphep',
'children'=>[
['link' => '#','name' => __('model.editgiayphep')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-giayphep :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-giayphep>
@endsection
