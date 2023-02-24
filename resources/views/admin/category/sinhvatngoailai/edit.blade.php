@extends('admin.layouts.index', [
'pageTitle' => __('model.editsinhvatngoailai')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'sinhvatngoailai',
'children'=>[
['link' => '#','name' => __('model.editsinhvatngoailai')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-sinhvat-ngoailai :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-sinhvat-ngoailai>
@endsection
