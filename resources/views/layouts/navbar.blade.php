<nav class="navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-btn">
            <button type="button" class="btn-toggle-offcanvas"><i class="lnr lnr-menu fa fa-bars"></i></button>
        </div>
        <div class="navbar-brand">    
            @if(get_setting('logo'))<a href="/"><img src="{{ get_setting('logo') }}" style="height:28px;width:auto;"  class="img-responsive logo"></a>@endif
        </div>
        <div class="navbar-right">
            <form id="navbar-search" class="navbar-form search-form">
                <div id="navbar-menu float-left">
                    <ul class="nav navbar-nav">
                        @foreach(config('entigi.menu') as $key => $access_menu)
                            @if(\Auth::user()->user_access_id==$key)
                                @foreach($access_menu as $key => $menu)
                                    <li class="{{  count($menu['sub_menu'])>0?"dropdown" :'' }}">
                                        <a href="{{ $menu['link']=="#" ? "#" : route($menu['link']) }}" class="text-info {{count($menu['sub_menu'])>0?'dropdown-toggle':''}} icon-menu px-1" {{ count($menu['sub_menu'])>0?'data-toggle=dropdown':''}}>{{$menu['title']}}</a>
                                        @if(count($menu['sub_menu']))
                                            <ul class="{{  count($menu['sub_menu'])>0?'dropdown-menu':'' }} user-menu menu-icon">
                                                @foreach($menu['sub_menu'] as $sub_menu)
                                                    <li><a href="{{ route($sub_menu['link']) }}">{{$sub_menu['title']}}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
            </form>
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <li class="d-none d-sm-inline-block d-md-none d-lg-inline-block">
                        {{\Auth::user()->name}} <small>({{\Auth::user()->access->name}})</small>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown"><i class="icon-equalizer"></i></a>
                        <ul class="dropdown-menu user-menu menu-icon">
                            <li class="menu-heading">ACCOUNT SETTINGS</li>
                            <li><a href="{{route('profile')}}"><i class="icon-note"></i> <span>My Profile</span></a></li>
                            @if(\Auth::user()->user_access_id==1)
                                <li><a href="{{route('setting')}}"><i class="icon-equalizer"></i> <span>Setting</span></a></li>
                                <li><a href="{{route('back-to-admin')}}" class="text-danger"><i class="fa fa-arrow-right"></i> <span>Back to Admin</span></a></li>
                            @endif
                        </ul>
                    </li>
                    <li><a href="" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="icon-menu"><i class="icon-login"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
