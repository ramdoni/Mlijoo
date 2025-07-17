<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            @if(\Auth::user()->profile_photo_path!="")
                <img src="{{ \Auth::user()->profile_photo_path }}" class="rounded-circle user-photo" alt="User Profile Picture">
            @endif
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong>{{ isset(\Auth::user()->name)?\Auth::user()->name :''}}</strong></a>
                <ul class="dropdown-menu dropdown-menu-right account">
                    <li><a href="{{route('profile')}}"><i class="icon-user"></i>My Profile</a></li>
                    <li><a href="{{route('setting')}}"><i class="icon-settings"></i>Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>
        </div>
        <!-- Nav tabs -->
        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane active" id="tab_anggota">
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">    
                        @if(\Auth::user()->user_access_id==1)<!--Administrator-->                   
                            <li class="{{ Request::segment(1) === 'dashboard' ? 'active' : null }}">
                                <a href="/"><i class="icon-home"></i> <span>Dashboard</span></a>
                            </li>
                            <li class="{{ (Request::segment(1) === 'product') ? 'active' : null }}">
                                <a href="{{route('product.index')}}"><i class="fa fa-bank"></i>Inventory</a>
                            </li>
                            <li class="{{ (Request::segment(1) === 'bank-account') ? 'active' : null }}">
                                <a href="{{route('bank-account.index')}}"><i class="fa fa-bank"></i>Bank Account</a>
                            </li>
                            <li class="{{ (Request::segment(1) === 'user') ? 'active' : null }}">
                                <a href="{{route('users.index')}}"><i class="fa fa-users"></i>User</a>
                            </li>
                            <li class="{{ (Request::segment(1) === 'log-activity') ? 'active' : null }}">
                                <a href="{{route('log-activity.index')}}"><i class="fa fa-history"></i>Log Activity</a>
                            </li>
                            <li class="{{ (Request::segment(1) === 'setting') ? 'active' : null }}">
                                <a href="javascript:void(0)" class="has-arrow"><i class="fa fa-gear"></i><span>Pengaturan</span></a>
                                <ul>
                                    <li class="{{ Request::segment(1) === 'setting' ? 'active' : null }}"><a href="{{route('setting')}}">Umum</a></li>
                                    <li class="{{ Request::segment(1) === 'simpanan' ? 'active' : null }}"><a href="{{route('simpanan.index')}}">Simpanan</a></li>
                                    <li class="{{ Request::segment(1) === 'jenis-pinjaman' ? 'active' : null }}"><a href="{{route('jenis-pinjaman.index')}}">Pinjaman</a></li>
                                </ul>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>    
            
            
            <div class="tab-pane {{(in_array(Request::segment(1),['product-supplier','purchase-order-supplier']) ? 'active':'')}}" id="menu">
                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu">    

                        @if(\Auth::user()->user_access_id==7)<!--Supplier-->                   
                         
                            <li class="{{ (Request::segment(1) === 'product-supplier') ? 'active' : null }}">
                                <a href="{{route('product-supplier.index')}}"><i class="fa fa-bank"></i>Product</a>
                            </li>
                            <li class="{{ (Request::segment(1) === 'purchase-order-supplier') ? 'active' : null }}">
                                <a href="{{route('purchase-order-supplier.index')}}"><i class="fa fa-gear"></i>Purchase Order</a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>    
        </div>          
    </div>
</div>
