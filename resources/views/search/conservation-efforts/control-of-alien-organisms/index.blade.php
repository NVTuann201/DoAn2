@extends('layouts.app')
@section('content')
<search-control-of-alien-organisms>
</search-control-of-alien-organisms>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
