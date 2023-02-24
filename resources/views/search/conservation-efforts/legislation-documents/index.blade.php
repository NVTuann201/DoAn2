@extends('layouts.app')
@section('content')
<search-legislation-documents>
</search-legislation-documents>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $(".stickyNav").addClass("hasOffset");
    });
</script>
@endsection
