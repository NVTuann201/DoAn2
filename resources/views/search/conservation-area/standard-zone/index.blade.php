@extends('layouts.app')
@section('content')
<search-standard-zone>
</search-standard-zone>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
