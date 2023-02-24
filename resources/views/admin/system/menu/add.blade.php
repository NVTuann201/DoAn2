@extends('admin.layouts.index', [
'pageTitle' => __('model.add_menu'),
'breadcrumbs' => [
        ['link' => '#', 'name' => 'System'],
        ['link' => route('system.menus'), 'name' => __('model.menu')],
        ['link' => '#', 'name' => __('model.add_menu')]
    ]
])

@section('content-detail')
    <system-menu-add></system-menu-add>
@endsection