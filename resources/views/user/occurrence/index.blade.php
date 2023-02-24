@extends('layouts.app')

@section('content')
    <search-occurrence-component :dataset="{{json_encode($dataset)}}"></search-occurrence-component>
@endsection