@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection
@section('content')
    <detail-region-component :value="{{json_encode($id)}}"></detail-region-component>
@endsection
@section('script')
    <script>
      $(document).ready(function () {
        $(".stickyNav").addClass("hasOffset");
      });
    </script>
@endsection