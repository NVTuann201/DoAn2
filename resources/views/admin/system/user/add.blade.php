@extends('admin.layouts.index', [
'pageTitle' => __('model.add_user')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'users',
'children'=>[
['link' => '#','name' => __('model.add_user')]
]
])
@endBreadcrumb
@endsection


@section('content-detail')
<system-user-add :danhmucs="{{json_encode($danhmucs)}}"></system-user-add>
@endsection