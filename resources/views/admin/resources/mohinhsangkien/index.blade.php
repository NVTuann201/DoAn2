@extends('admin.layouts.index', [
'pageTitle' => __('model.mohinhsangkien'),
])

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection

@section('content-detail')
<mohinh-sangkien :hinhthucs="{{$hinhthucs}}" :quanhuyens="{{$quanhuyens}}" :phancaps="{{$phancaps}}"></mohinh-sangkien>
@endsection
