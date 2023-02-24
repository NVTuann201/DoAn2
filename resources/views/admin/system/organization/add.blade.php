@extends('admin.layouts.index', [
    'pageTitle' => __('model.add_organization'),
    'breadcrumbs' => [
        ['link' => '#', 'name' => 'System'],
        ['link' => route('organizations'), 'name' => __('model.organization')],
        ['link' => route('organizations.create'), 'name' => __('model.add_organization')]
    ]

])

@section('content-detail')
    <system-organization-add></system-organization-add>
@endsection