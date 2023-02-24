@extends('admin.layouts.index', [
'pageTitle' => __('model.dieuuocquocte'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dieu-uoc></dieu-uoc>
@endsection
