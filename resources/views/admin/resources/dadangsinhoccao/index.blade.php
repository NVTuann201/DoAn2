@extends('admin.layouts.index', [
'pageTitle' => __('model.khuvucdadangsinhhoccao'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<dadang-sinhhoccao></dadang-sinhhoccao>
@endsection
