@extends('admin.layouts.index', [
'pageTitle' => __('model.chuongtrinhdetai'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<kiemsoat-svnlxh :quanhuyens="{{$quanhuyens}}"></kiemsoat-svnlxh>
@endsection
