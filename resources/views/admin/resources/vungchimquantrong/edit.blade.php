@extends('admin.layouts.index', [
'pageTitle' => __('model.editvungchimquantrong')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'vungchimquantrong',
'children'=>[
['link' => '#','name' => __('model.editvungchimquantrong') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-vungchim-quantrong :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-vungchim-quantrong>
@endsection
