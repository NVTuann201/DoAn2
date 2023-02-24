@extends('admin.layouts.index', [
'pageTitle' => __('model.add_role'),
'breadcrumbs' => [
        ['link' => '#', 'name' => 'System'],
        ['link' => route('system.roles'), 'name' => __('model.role')],
        ['link' => '#', 'name' => __('model.add_role')],
    ]
])

@section('content-detail')
    <system-role-add></system-role-add>
@endsection