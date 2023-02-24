@extends('admin.layouts.index', [
'pageTitle' => __('model.adddatngapnuoc')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'datngapnuocquantrong',
'children'=>[
['link' => '#','name' => __('model.adddatngapnuoc') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-dat-ngapnuoc :danhmucs="{{$danhmucs}}"></add-dat-ngapnuoc>
@endsection
