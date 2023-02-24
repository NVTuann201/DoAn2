@extends('layouts.app')
@section('css')
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.5.0/mapbox-gl.css' rel='stylesheet' />
@endsection
@section('content')
    <search-protectedarea-component :desig_type="{{json_encode($type)}}"></search-protectedarea-component>
@endsection
