@extends('admin.layouts.index', [
'pageTitle' => __('model.baocaoapluc'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<thongke-apluc :danhmucs="{{$danhmucs}}"></thongke-apluc>
@endsection
