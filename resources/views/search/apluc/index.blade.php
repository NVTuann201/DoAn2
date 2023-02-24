@extends('layouts.app')
@section('content')
<search-apluc :searchdata="{{$searchdata}}">
</search-apluc>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection