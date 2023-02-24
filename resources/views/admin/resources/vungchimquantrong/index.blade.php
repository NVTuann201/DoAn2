@extends('admin.layouts.index', [
'pageTitle' => __('model.vungchimquantrong'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<vungchim-quantrong></vungchim-quantrong>
@endsection
