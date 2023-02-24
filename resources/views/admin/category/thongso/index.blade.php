@extends('admin.layouts.index', [
'pageTitle' => __('model.thongso'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<thong-so :chiThi="{{$chiThi}}"></thong-so>
@endsection
