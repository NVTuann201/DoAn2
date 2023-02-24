@extends('admin.layouts.index', [
'pageTitle' => __('model.user'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<system-user-index usersystem="{{json_encode($user)}}"></system-user-index>
@endsection
