@extends('admin.layouts.index', [
'pageTitle' => __('model.menu'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<system-menu-index></system-menu-index>
@endsection
