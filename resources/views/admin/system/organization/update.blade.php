@extends('admin.layouts.index', [
    'pageTitle' => __('model.update_organization'),
    'breadcrumbs' => [
        ['link' => '#', 'name' => 'System'],
        ['link' => route('organizations'), 'name' => __('model.organization')],
        ['link' => '#', 'name' => __('model.update_organization')]
    ]
])

@section('content-detail')
    <system-organization-update value="{{$organization}}" :imageurl="'{{!empty($image) ? $image : 'null'}}'"></system-organization-update>
@endsection