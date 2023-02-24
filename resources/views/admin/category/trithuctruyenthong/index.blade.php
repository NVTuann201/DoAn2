@extends('admin.layouts.index', [
'pageTitle' => __('model.genetic'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<trithuc-truyenthong :nhomtrithucs="{{$nhomtrithucs}}"></trithuc-truyenthong>
@endsection
