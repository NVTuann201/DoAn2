@extends('admin.layouts.index', [
'pageTitle' => __('model.kinhphi'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<kinh-phi :danhmucs="{{$danhmucs}}"></kinh-phi>
@endsection
