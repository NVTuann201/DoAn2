@extends('admin.layouts.index', [
'pageTitle' => __('model.addtaxonloai')
])

@section('breadcrumb')
@breadcrumb([
'parent'=>'taxon',
'children'=>[
['link' => '#','name' => __('model.addtaxonloai')]
]
])
@endBreadcrumb
@endsection
@section('content-detail')
<detail-loai-taxon type='create' :danhmucs="{{$danhmucs}}"></detail-loai-taxon>
@endsection
