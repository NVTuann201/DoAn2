@extends('admin.layouts.index', [
'pageTitle' => __('model.datasets'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<category-dataset-index></category-dataset-index>
@endsection
