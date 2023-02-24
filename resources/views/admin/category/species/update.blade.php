@extends('admin.layouts.index', [
'pageTitle' => __('model.species')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'species',
'children'=>[
['link' => '#','name' => __('model.species')]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<category-species-update id="{{$species->id}}" data="{{$rank}}" value="{{$species}}"></category-species-update>
@endsection
