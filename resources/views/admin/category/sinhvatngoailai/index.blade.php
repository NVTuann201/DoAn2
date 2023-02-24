@extends('admin.layouts.index', [
'pageTitle' => __('model.sinhvatngoailai'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<sinhvat-ngoailai></sinhvat-ngoailai>
@endsection
