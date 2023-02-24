@extends('layouts.app')
@section('content')
<search-genetic-knowledge >
</search-genetic-knowledge>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
