@extends('admin.layouts.index', [
'pageTitle' => __('model.userlog'),
])

@section('content-detail')
<system-userlog-index></system-userlog-index>
@endsection
