@extends('layouts.form')

@section('content')
<detail-occurrence-component :value="{{$model}}"></detail-occurrence-component>
@endsection