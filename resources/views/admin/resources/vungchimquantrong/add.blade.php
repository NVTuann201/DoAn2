@extends('admin.layouts.index', [
'pageTitle' => __('model.addvungchimquantrong')
])


@section('breadcrumb')
@breadcrumb([
'parent'=>'vungchimquantrong',
'children'=>[
['link' => '#','name' => __('model.addvungchimquantrong') ]
]
])
@endBreadcrumb
@endsection

@section('content-detail')
<add-vungchim-quantrong :danhmucs="{{$danhmucs}}"></add-vungchim-quantrong>
@endsection
