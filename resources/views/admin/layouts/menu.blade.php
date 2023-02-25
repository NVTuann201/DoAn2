@foreach ($menus->tree as $menu)
@if (count($menu->children) > 0)
<li>
    @if(Route::has($menu->router_link))
    <a href="{{route($menu->router_link)}}"><i class="m-r-10 {{$menu->fa_icon}}"
            class="{{ ($menu->router_link == Route::currentRouteName()) ? 'active' : '' }}"></i>
        <span class="hide-menu">{{$menu->name}}</span>
    </a>
    @else
    <a href="#">
        <i class="m-r-10 {{$menu->fa_icon}}"></i>
        <span class="hide-menu">{{$menu->name}}</span>
        @endif
        <span class="label label-rouded label-inverse pull-right">{{count($menu->children)}}</span>
        <span class="pull-right-container">
            <i class="fas fa arrow"></i>
        </span>
    </a>
    <ul class="nav nav-second-level">
        @foreach ($menu->children as $menu_detail)
        @if (count($menu_detail->children) > 0)
        <li>
            @if(Route::has($menu_detail->router_link))
            <a href="{{route($menu_detail->router_link)}}">
                <i class="m-r-10 {{$menu_detail->fa_icon}}"></i>
                <span class="hide-menu">{{$menu_detail->name}}</span>
            </a>
            @else
            <a href="#"><i class="m-r-10 {{$menu_detail->fa_icon}}"></i>
                <span>{{$menu_detail->name}}</span>
                @endif
                <span class="pull-right-container">
                    <i class="fas fa arrow pull-right"></i>
                </span>
            </a>
            <ul class="nav nav-third-level">
                @foreach ($menu_detail->children as $menu_detail2)
                @if(Route::has($menu_detail2->router_link))
                <li>
                    <a href="{{route($menu_detail2->router_link)}}"
                        class="{{ ($menu_detail2->router_link == Route::currentRouteName()) ? 'active-menu active' : '' }}">
                        <i class="m-r-10 {{$menu_detail2->fa_icon}}"></i>
                        <span class="hide-menu">{{$menu_detail2->name}}</span>
                    </a>
                </li>
                @else
                <li>
                    <a href="#">
                        <i class="m-r-10 {{$menu_detail2->fa_icon}}"></i>
                        <span class="hide-menu">{{$menu_detail2->name}}</span>
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
        </li>
        @elseif(Route::has($menu_detail->router_link))
        <li>
            <a class="{{ ($menu_detail->router_link == Route::currentRouteName()) ? 'active-menu active' : '' }}"
                href="{{route($menu_detail->router_link)}}">
                <i class="m-r-10  {{$menu_detail->fa_icon}}"></i>
                <span class="hide-menu">{{$menu_detail->name}}</span>
            </a>
        </li>
        @else
        <li>
            <a href="#">
                <i class="m-r-10  {{$menu_detail->fa_icon}}"></i>
                <span class="hide-menu">{{$menu_detail->name}}</span>
            </a>
        </li>
        @endif
        @endforeach
    </ul>
</li>
@elseif (Route::has($menu->router_link))
<li>
    <a href="{{route($menu->router_link)}}"
        class="{{ ($menu->router_link == Route::currentRouteName()) ? 'active' : '' }}">
        <i class="m-r-10 fa {{$menu->fa_icon}} "></i>
        <span>{{$menu->name}}</span>
    </a>
</li>
@else
<li><a href="{{null}}"><i class="m-r-10  {{$menu->fa_icon}}"></i> <span class="hide-menu">{{$menu->name}}</span></a>
</li>
@endif
@endforeach
@section('script')
<script>
    $(function() {
        if ($('a').hasClass('active-menu')) {
            $("a.active-menu").parents().eq(4).addClass("active")
            $("a.active-menu").parents().eq(2).addClass("active");
            $("a.active-menu").parents().eq(1).prev().addClass("active");
        }
    });
</script>
@endsection
