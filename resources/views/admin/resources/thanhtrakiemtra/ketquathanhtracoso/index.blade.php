@extends('admin.layouts.index', [
'pageTitle' => __('model.ketquathanhtracoso'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<ketquathanhtracoso :danhmucs="{{$danhmucs}}"></ketquathanhtracoso>
@endsection
