@extends('admin.layouts.index', [
'pageTitle' => __('model.khucanhquansinhthai'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<canhquan-sinhthai></canhquan-sinhthai>
@endsection
