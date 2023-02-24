@extends('admin.layouts.index', [
'pageTitle' => __('model.addsinhvatngoailai')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'sinhvatngoailai',
'children'=>[
['link' => '#','name' => __('model.addsinhvatngoailai')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-sinhvat-ngoailai :danhmucs="{{$danhmucs}}"></add-sinhvat-ngoailai>
@endsection
