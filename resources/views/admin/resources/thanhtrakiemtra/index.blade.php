@extends('admin.layouts.index', [
'pageTitle' => __('model.thanhtrakiemtra'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<thanhtra-kiemtra></thanhtra-kiemtra>
@endsection
