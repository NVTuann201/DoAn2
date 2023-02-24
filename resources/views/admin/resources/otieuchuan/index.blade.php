@extends('admin.layouts.index', [
'pageTitle' => __('model.otieuchuan'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<o-tieuchuan :diadiems="{{$diadiems}}"></o-tieuchuan>
@endsection
