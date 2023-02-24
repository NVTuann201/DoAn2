@extends('layouts.form')

@section('content')

    <detail-publisher-component
            :value="{{$model}}"
            :image="'{{!empty($image) ? $image : 'null'}}'"
            :year="{{$datasetWithYear}}"
            :dataset="{{$occurrencesDataset}}"
            :protected="{{$occurrencesProtectedArea}}"
            :region="{{$occurrencesRegion}}">
    </detail-publisher-component>
@endsection