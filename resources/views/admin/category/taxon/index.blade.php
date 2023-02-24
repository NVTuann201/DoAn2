@extends('admin.layouts.index', [
'pageTitle' => __('model.taxonloai')
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<loai-taxon></loai-taxon>
@endsection
