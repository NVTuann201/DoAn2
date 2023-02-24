@extends('admin.layouts.index', [
'pageTitle' => __('model.hesinhthai'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<he-sinhthai></he-sinhthai>
@endsection
