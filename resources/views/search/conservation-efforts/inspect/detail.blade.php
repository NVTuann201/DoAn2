@extends('layouts.form')

@section('content')
<detail-inspect :data="{{$data}}" type="{{ $type }}">
</detail-inspect>
@endsection
