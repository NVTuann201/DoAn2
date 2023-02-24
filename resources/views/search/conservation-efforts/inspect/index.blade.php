@extends('layouts.app')
@section('content')
<search-inspect>
</search-inspect>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
