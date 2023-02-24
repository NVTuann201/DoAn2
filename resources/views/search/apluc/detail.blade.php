@extends('layouts.app')
@section('content')
    <detail-apluc :value="{{json_encode($data)}}"></detail-apluc>
@endsection
@section('script')
    <script>
      $(document).ready(function () {
        $(".stickyNav").addClass("hasOffset");
      });
    </script>
@endsection