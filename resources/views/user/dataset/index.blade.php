@extends('layouts.app')

@section('breadcrumb')
@breadcrumb()
@endBreadcrumb
@endsection


@section('content')
<search-dataset-component :dataset_type="{{json_encode($type)}}" :publisher="{{json_encode($publisher)}}" :protectedarea="{{json_encode($protectedarea)}}"></search-dataset-component>
@endsection
