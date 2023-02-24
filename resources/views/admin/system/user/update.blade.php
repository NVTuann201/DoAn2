@extends('admin.layouts.index', [
'pageTitle' => __('model.update_user')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'users',
'children'=>[
['link' => '#','name' => __('model.update_user')]
]
])
@endBreadcrumb
@endsection


@section('content-detail')
<user-update-component :value="{{json_encode($user)}}" :danhmucs="{{json_encode($danhmucs)}}"></user-update-component>
@endsection
