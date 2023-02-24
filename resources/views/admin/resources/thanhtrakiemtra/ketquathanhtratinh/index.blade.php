@extends('admin.layouts.index', [
'pageTitle' => __('model.ketquathanhtratinh'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<ketquathanhtratinh :danhmucs="{{$danhmucs}}"></ketquathanhtratinh>
@endsection
