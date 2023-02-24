@extends('admin.layouts.index', [
'pageTitle' => __('model.quantrac'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<quan-trac></quan-trac>
@endsection
