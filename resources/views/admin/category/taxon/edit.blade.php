@extends('admin.layouts.index', [
'pageTitle' => __('model.edittaxonloai')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'taxon',
'children'=>[
['link' => '#','name' => __('model.edittaxonloai')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<detail-loai-taxon type='update' :value="{{$model}}" :id="{{$model['id']}}" :danhmucs="{{$danhmucs}}"></detail-loai-taxon>
@endsection
