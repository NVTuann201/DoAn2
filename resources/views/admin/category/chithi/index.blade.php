@extends('admin.layouts.index', [
'pageTitle' => __('model.chithi'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<chi-thi></chi-thi>
@endsection
