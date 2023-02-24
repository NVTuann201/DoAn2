@extends('admin.layouts.index', [
'pageTitle' => __('model.bangtin'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<bangtin-tonghop :danhmucs="{{$danhmucs}}"></bangtin-tonghop>
@endsection
