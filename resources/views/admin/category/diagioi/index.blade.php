@extends('admin.layouts.index', [
'pageTitle' => __('model.diagioi'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dia-gioi></dia-gioi>
@endsection
