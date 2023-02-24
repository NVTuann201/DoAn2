@extends('admin.layouts.index', [
'pageTitle' => __('model.update_protected_area')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'protectedareas',
'children'=>[
['link' => '#','name' => __('model.update_protected_area') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<category-protectedarea-update value="{{$protectedarea}}"></category-protectedarea-update>
@endsection
