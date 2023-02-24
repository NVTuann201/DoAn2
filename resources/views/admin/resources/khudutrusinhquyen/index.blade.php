@extends('admin.layouts.index', [
'pageTitle' => __('model.khudutrusinhquyen'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dutru-sinhquyen></dutru-sinhquyen>
@endsection
