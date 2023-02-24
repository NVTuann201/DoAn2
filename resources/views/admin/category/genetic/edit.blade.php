@extends('admin.layouts.index', [
'pageTitle' => __('model.editgenetic')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'genetic',
'children'=>[
['link' => '#','name' => __('model.editgenetic')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-nguongen :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-nguongen>
@endsection
