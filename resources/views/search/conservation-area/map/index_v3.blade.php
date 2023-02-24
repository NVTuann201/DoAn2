@extends('layouts.app')
@section('content')
<search-map-v3>
</search-map-v3>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
