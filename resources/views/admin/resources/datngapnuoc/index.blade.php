@extends('admin.layouts.index', [
'pageTitle' => __('model.datngapnuoc'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dat-ngapnuoc></dat-ngapnuoc>
@endsection
