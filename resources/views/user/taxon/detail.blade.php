@extends('layouts.form')

@section('content')
<detail-taxon-component :data="{{$data}}" :images="{{$images}}"></detail-taxon-component>
@endsection
