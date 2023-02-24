@extends('admin.layouts.index', [
'pageTitle' => __('model.editdatngapnuoc'),
'breadcrumbs' => [
['link' => '#', 'name' => 'Resource'],
['link' => route('datngapnuocquantrong'), 'name' => __('model.editdatngapnuoc')]
]
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'datngapnuocquantrong',
'children'=>[
['link' => '#','name' => __('model.editdatngapnuoc') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<edit-dat-ngapnuoc :danhmucs="{{$danhmucs}}" :data="{{$data}}"></edit-dat-ngapnuoc>
@endsection
