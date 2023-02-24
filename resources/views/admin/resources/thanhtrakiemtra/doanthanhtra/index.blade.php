@extends('admin.layouts.index', [
'pageTitle' => __('model.doanthanhtra'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<doanthanhtra :danhmucs="{{$danhmucs}}"></doanthanhtra>
@endsection
