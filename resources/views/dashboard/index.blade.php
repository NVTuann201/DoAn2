@extends('admin.layouts.index', [
'pageTitle' => __('model.thongke'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dashboard-home></dashboard-home>
@endsection
