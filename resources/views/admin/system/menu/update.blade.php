@extends('admin.layouts.index', [
'pageTitle' => __('model.update_menu'),
'breadcrumbs' => [
        ['link' => '#', 'name' => 'System'],
        ['link' => route('system.menus'), 'name' => __('model.menu')],
        ['link' => '#', 'name' => __('model.update_menu')]
    ]
])

@section('content-detail')
    <system-menu-update value="{{$menu}}"></system-menu-update>
@endsection