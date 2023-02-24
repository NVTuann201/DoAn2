@extends('admin.layouts.index', [
'pageTitle' => 'Thông tin chi tiết bộ dữ liệu',
'breadcrumbs' => [
        ['link' => '#', 'name' => 'Category'],
        ['link' => route('dataset'), 'name' => __('model.datasets')],
        ['link' => '#', 'name' => 'Chi tiết bộ dữ liệu']
    ]
])

@section('content-detail')
    <category-dataset-detail-taxon value={{$id}}></category-dataset-detail-taxon>
@endsection