@extends('admin.layouts.index', [
'pageTitle' => __('model.organization'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<system-organization-index></system-organization-index>
@endsection
