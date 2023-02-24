@extends('layouts.app')
@section('content')
<search-map-v2>
</search-map-v2>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
