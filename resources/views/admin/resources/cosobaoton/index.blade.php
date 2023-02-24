@extends('admin.layouts.index', [
'pageTitle' => __('model.cosobaoton'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<coso-baoton></coso-baoton>
@endsection
