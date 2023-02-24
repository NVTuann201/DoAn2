@extends('layouts.app')

@section('content')
<search-publisher-component :publisher="{{ json_encode($publisher) }}"></search-publisher-component>
@endsection