@extends('admin.layouts.index', [
'pageTitle' => 'Công cụ',
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<system-tools-index></system-tools-index>
@endsection
