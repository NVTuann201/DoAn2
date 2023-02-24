@extends('layouts.app')
@section('content')
<search-map :quan_huyen="{{$quan_huyen}}">
</search-map>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
