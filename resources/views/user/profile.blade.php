@extends('layouts.form')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dist/vue-multiselect.min.css') }}">
@endsection
@section('content')
    <div ui-view="main" class="viewContentWrapper ng-scope">
        <div class="site-content ng-scope">
            <div class="site-content__page">
                <div class="user white-background">
                    <article class="wrapper-horizontal-stripes ng-scope">
                        <div class="horizontal-stripe--paddingless white-background">
                            <div class="container--normal">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="tabs__wrapper">
                                            <nav class="tabs tabs--noBorder">
                                                <ul>
                                                    <li class="tab isActive">
                                                        <a href="/user/profile">{{__('label.profile')}}</a>
                                                    </li>
                                                    <li class="tab">
                                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                            <span class="gb-icon-logout"></span>
                                                            <span
                                                                class="ng-scope">{{__('nbds_lang.menu_logout')}}</span>
                                                        </a>
                                                    </li>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <profile-component :value="{{$user}}"></profile-component>
                    </article>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
      $(document).ready(function () {
        $(".stickyNav").addClass("hasOffset");
      });
    </script>
@endsection