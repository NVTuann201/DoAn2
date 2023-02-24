@extends('layouts.app')
@section('content')
<bieudo-loai :dulieubieudo="{{$dulieubieudo}}">
</bieudo-loai>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection