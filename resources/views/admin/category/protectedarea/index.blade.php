@extends('admin.layouts.index', [
'pageTitle' => __('model.protected_area'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<category-protectedarea-index></category-protectedarea-index>
@endsection
