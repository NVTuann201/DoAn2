@extends('admin.layouts.index', [
'pageTitle' => __('model.genetic'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<nguon-gen :phanloai="{{$phanloai}}"></nguon-gen>
@endsection
