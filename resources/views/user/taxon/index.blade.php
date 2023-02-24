@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <search-taxon-component :dataset="{{json_encode($dataset)}}"></search-taxon-component>
@endsection
