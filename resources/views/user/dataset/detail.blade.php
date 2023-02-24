@extends('layouts.form')

@section('content')
    <detail-dataset-component
            :value="{{$model}}"
            :king_doms="{{$king_doms}}"
            :phylums="{{$phylums}}"
            :occurrences="{{$occurrences}}"
            :metrcisdata="{{$datasetWithYear}}"
            :data = "{{$datatree}}"
            :user="{{!empty($user)?$user:'null'}}"
            :countImage="{{$images}}"
    ></detail-dataset-component>
@endsection
