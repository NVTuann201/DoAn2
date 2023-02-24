@extends('layouts.app')
@section('content')
<search-annual-budget>
</search-annual-budget>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
