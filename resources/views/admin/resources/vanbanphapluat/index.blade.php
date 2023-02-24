@extends('admin.layouts.index', [
'pageTitle' => __('model.vanbanphapluat'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<vanban-phapluat :danhmucs="{{$danhmucs}}"></vanban-phapluat>
@endsection
