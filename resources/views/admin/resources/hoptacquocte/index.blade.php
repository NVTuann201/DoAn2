@extends('admin.layouts.index', [
'pageTitle' => __('model.hoptacquocte')
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<hoptac-quocte></hoptac-quocte>
@endsection
