@extends('admin.layouts.index', [
'pageTitle' => __('model.species'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<category-species-index></category-species-index>
@endsection
