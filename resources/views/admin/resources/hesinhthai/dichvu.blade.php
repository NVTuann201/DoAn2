@extends('admin.layouts.index', [
'pageTitle' => __('model.dichvuhesinhthai'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dichvu-hesinhthai :danhmucs="{{$danhmucs}}"></dichvu-hesinhthai>
@endsection
