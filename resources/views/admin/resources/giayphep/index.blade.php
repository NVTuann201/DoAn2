@extends('admin.layouts.index', [
'pageTitle' => __('model.giayphep'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<giay-phep :tengiayphep="{{$tenGiayPheps}}" :loaigiayphep="{{$loaiGiays}}"></giay-phep>
@endsection
