@extends('admin.layouts.index', [
'pageTitle' => __('model.update_role'),
'breadcrumbs' => [
        ['link' => '#', 'name' => 'System'],
        ['link' => route('system.roles'), 'name' => __('model.role')],
        ['link' => '#', 'name' => __('model.update_role')],
    ]
])

@section('content-detail')
    <system-role-update value="{{$role}}"></system-role-update>
@endsection