@extends('admin.layouts.index', ['pageTitle' => __('model.profile')])

@section('content-detail')
    <auth-profile-index value="{{$user}}" :image="'{{!empty($image) ? $image : 'null'}}'"></auth-profile-index>
@endsection