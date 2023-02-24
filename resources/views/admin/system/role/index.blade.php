@extends('admin.layouts.index', [
'pageTitle' => __('model.role'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<system-role-index></system-role-index>
@endsection
