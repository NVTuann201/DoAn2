@extends('admin.layouts.index', [
'pageTitle' => __('model.hanhlangdadangsinhhoc'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<hanhlang-sinhhoc></hanhlang-sinhhoc>
@endsection
