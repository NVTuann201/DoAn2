<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav slimscrollsidebar">
        <div class="sidebar-head">
            <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
        </div>
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                {{-- <div>
                    @if (!empty($image))
                    <img src="{{ !empty($image) ? $image : '' }}" alt="user-img" class="img-circle" />
                    @else
                    <img src="{{ $user->avatar_url }}" alt="user-img" width="36" class="img-circle" />
                    @endif
                </div> --}}
                {{-- <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ $user->name }}<span class="caret"></span></a> --}}
                <ul class="dropdown-menu animated flipInY">
                    <li><a href="{{ route('profile') }}"><i class="ti-user"></i>&nbsp;{{__('label.profile')}}</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>&nbsp;{{__('label.logout')}}</a></li>
                </ul>
            </div>
        </div>
        <ul class="nav" id="side-menu">
            @include('admin.layouts.menu')
            {{-- <li><a href="#" class="waves-effect" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();"><i class="mdi mdi-logout fa-fw"></i> <span class="hide-menu">&nbsp;{{__('label.logout')}}</span></a></li>
            <li class="devider"></li>
            <li><a href="#" class="waves-effect"><i class="far fa-circle text-inverse"></i> <span class="hide-menu">&nbsp;{{__('label.documentation')}}</span></a></li>
            <li><a href="#" class="waves-effect"><i class="far fa-circle text-inverse"></i> <span class="hide-menu">&nbsp;{{__('label.faqs')}}</span></a></li> --}}
        </ul>
    </div>
</div>
