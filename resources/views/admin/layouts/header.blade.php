<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <div class="top-left-part">
            <!-- Logo -->
            <a class="logo" href="/">
                <b>
                    <img src="{{ URL::to('/') . '/images/logo/admin/logo.png'}}" alt="home" class="light-logo" style="height: 80%;" />
                </b>
                <i class="fa fa-home"></i>
                <span><strong>Trang chủ</strong></span>
            </a>
        </div>
        <!-- /Logo -->
        <!-- Search input and Toggle icon -->
        <ul class="nav navbar-top-links navbar-left">
            {{-- <li><a href="javascript:void(0)" class="open-close waves-effect waves-light"><i class="ti-menu"></i></a>
            </li> --}}
            <li class="dropdown" style="display: none">
                <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"> <i class="mdi mdi-gmail"></i>
                    <div class="notify"><span class="heartbit"></span> <span class="point"></span></div>
                </a>
                <ul class="dropdown-menu mailbox animated bounceInDown">
                    <li>
                        <div class="drop-title">Bạn có 4 thông báo mới</div>
                    </li>
                    {{-- <li>
                        <div class="message-center">
                            <a href="#">
                                <div class="user-img"><img src="{{ URL::to('/') . '/images/users/pawandeep.jpg'}}"
                    alt="user" class="img-circle"> <span class="profile-status online pull-right"></span>
    </div>
    <div class="mail-contnet">
        <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
    </div>
    </a>
    <a href="#">
        <div class="user-img"><img src="{{ URL::to('/') . '/images/users/sonu.jpg'}}" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span></div>
        <div class="mail-contnet">
            <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span>
            <span class="time">9:10 AM</span>
        </div>
    </a>
    <a href="#">
        <div class="user-img"><img src="{{ URL::to('/') . '/images/users/arijit.jpg'}}" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span></div>
        <div class="mail-contnet">
            <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
        </div>
    </a>
    <a href="#">
        <div class="user-img"><img src="{{ URL::to('/') . '/images/users/pawandeep.jpg'}}" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span></div>
        <div class="mail-contnet">
            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
        </div>
    </a>
    </div>
    </li> --}}
    <li>
        <a class="text-center" href="javascript:void(0);"> <strong>Xem tất cả thông báo</strong> <i class="fa fa-angle-right"></i> </a>
    </li>
    </ul>
    <!-- /.dropdown-messages -->
    </li>
    <!-- .Task dropdown -->
    <li class="dropdown" style="display: none">
        <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"> <i class="mdi mdi-check-circle"></i>
            <div class="notify"><span class="heartbit"></span><span class="point"></span></div>
        </a>
        <ul class="dropdown-menu dropdown-tasks animated slideInUp">
            <li>
                <a href="#">
                    <div>
                        <p><strong>Task 1</strong> <span class="pull-right text-muted">40% Complete</span></p>
                        <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"><span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <p><strong>Task 2</strong> <span class="pull-right text-muted">20% Complete</span></p>
                        <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"><span class="sr-only">20% Complete</span>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <p><strong>Task 3</strong> <span class="pull-right text-muted">60% Complete</span></p>
                        <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%"><span class="sr-only">60% Complete (warning)</span>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#">
                    <div>
                        <p><strong>Task 4</strong> <span class="pull-right text-muted">80% Complete</span></p>
                        <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%"><span class="sr-only">80% Complete (danger)</span>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="text-center" href="#"> <strong>See All Tasks</strong> <i class="fa fa-angle-right"></i> </a>
            </li>
        </ul>
    </li>
    <!-- .Megamenu -->
    <!-- <li class="mega-dropdown"><a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"><span class="hidden-xs">Mega bbvb</span> <i class="icon-options-vertical"></i></a>
        <ul class="dropdown-menu mega-dropdown-menu animated bounceInDown">
            @foreach ($menus->mega as $key => $item)
            <li class="col-sm-3">
                <ul>
                    <li class="dropdown-header">{{$key}}</li>
                    @foreach ($item as $menu)
                    @if (Route::has($menu->router_link))
                    <li><a href="{{route($menu->router_link)}}">{{$menu->name}}</a></li>
                    @else
                    <li><a href="#">{{$menu->name}}</a></li>
                    @endif
                    @endforeach
                </ul>
            </li>
            @endforeach
        </ul>
    </li> -->
    <!-- /.Megamenu -->
    </ul>
    <ul class="nav navbar-top-links navbar-right pull-right">
        <li class="dropdown">
            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                {{-- @if (!empty($image))
                <img src="{{ !empty($image) ? $image : '' }}" alt="user-img" width="36" class="img-circle" />
                @else
                <img src="{{ $user->avatar_url }}" alt="user-img" width="36" class="img-circle" />
                @endif --}}
                <b class="hidden-xs">{{ $user->name }}</b><span class="caret"></span>
            </a>
            <ul class="dropdown-menu dropdown-user animated flipInY">
                <li>
                    <div class="dw-user-box">
                        {{-- <div class="u-img">
                            @if (!empty($image))
                            <img src="{{ !empty($image) ? $image : '' }}" alt="user" />
                            @else
                            <img src="{{ $user->avatar_url }}" alt="user" />
                            @endif
                        </div> --}}
                        <div class="u-text">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p><a href="{{ route('profile') }}" class="btn btn-rounded btn-danger btn-sm">{{__('label.profile')}}</a>
                        </div>
                    </div>
                </li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>&nbsp;{{__('label.logout')}}</a></li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </ul>
        </li>
    </ul>
    </div>
</nav>
