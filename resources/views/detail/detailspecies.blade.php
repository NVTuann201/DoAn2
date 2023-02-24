@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection
@section('content')
    <detail-species-component :value="{{json_encode($id)}}"></detail-species-component>
@endsection
@section('script')
    <script>
      $(document).ready(function () {
        $(".stickyNav").addClass("hasOffset");
      });
    </script>
@endsection