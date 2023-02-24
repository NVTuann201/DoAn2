@extends('layouts.app')
@section('content')
<search-ecosystem>
</search-ecosystem>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
