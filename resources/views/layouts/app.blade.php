<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ get_setting('favicon') }}" type="image/x-icon"> <!-- Favicon-->
        <title>@yield('title') - {{ get_setting('company') }}</title>
        <meta name="description" content="@yield('meta_description', config('app.name'))">
        <meta name="author" content="@yield('meta_author', config('app.name'))">
        @yield('meta')
        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')
        <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/jvectormap/jquery-jvectormap-2.0.3.min.css') }}"/>
        <link rel="stylesheet" href="{{ asset('assets/vendor/morrisjs/morris.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/vendor/toastr/toastr.min.css') }}"/>
        <!-- Custom Css --> 
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}?v=1">
        <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom.css').(env('APP_DEBUG')==true?'?date='.date('YmdHis') : '') }}">
        <script src="{{ asset('js/alpine.min.js') }}" defer></script>
        @stack('after-styles')
        @if (trim($__env->yieldContent('page-styles')))
            @yield('page-styles')
        @endif
        @livewireStyles
        <style>
            .theme-blue:before, .theme-blue:after {background:white !important;}
            .theme-blue #wrapper:before, .theme-blue #wrapper:after {background:white !important;}
        </style>
    </head>
    <body class="theme-blue layout-fullwidth">
    {{-- <body class="theme-blue"> --}}
        <!-- Page Loader -->
        <div class="page-loader-wrapper">
            <div class="loader">
                @if(get_setting('logo'))
                <div class="m-t-30">
                    <img src="{{get_setting('logo')}}" height="48" alt="{{get_setting('company')}}">
                </div>
                @endif
                <p>Please wait...</p>        
            </div>
        </div>
        <div id="wrapper">
            @include('layouts.navbar')
            @include('layouts.sidebar')
            <div id="main-content">
                @if (trim($__env->yieldContent('title')))
                    <div class="top-title">
                        <div class="row">
                            <div class="col-4 pl-0">
                                <h2> <a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> @yield('title')</h2>
                            </div>
                            <div class="col-8">
                                @stack('action')
                            </div>
                        </div>
                    </div>
                @endif
                <div class="container-fluid">
                    <div class="block-header">
                        <!-- <div class="col-lg-5 col-md-8 col-sm-12">                        
                            <h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> Analytical</h2>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">
                                    <i class="icon-home"></i></a></li>
                                <li class="breadcrumb-item">Dashboard</li>
                                <li class="breadcrumb-item active">Analytical</li>
                            </ul>
                        </div> -->
                        @if(session()->has('message-success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-check-circle"></i> {{session('message-success')}}
                        </div>
                        @endif
                        @if(session()->has('message-error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <i class="fa fa-times-circle"></i>  {{session('message-error')}}
                            </div>
                        @endif
                        <div class="alert alert-danger alert-dismissible" role="alert" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <i class="fa fa-times-circle"></i> <span class="message"></span>
                        </div>
                        <div class="alert alert-success alert-dismissible" role="alert" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <i class="fa fa-check-circle"></i> <span class="message"></span>
                        </div>
                    </div>
                    @yield('content')
                    {{$slot}}
                </div>
            </div>
        </div>
        <!-- Scripts -->
        @stack('before-scripts')
        <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>    
        <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/bundles/morrisscripts.bundle.js') }}"></script><!-- Morris Plugin Js -->
        <script src="{{ asset('assets/bundles/jvectormap.bundle.js') }}"></script> <!-- JVectorMap Plugin Js -->
        <script src="{{ asset('assets/bundles/knob.bundle.js') }}"></script>
        <script src="{{ asset('assets/bundles/mainscripts.bundle.js') }}"></script>
        <script src="{{ asset('assets/vendor/toastr/toastr.js') }}"></script>
        <script src="{{ asset('js/pusher.min.js') }}"></script>
        @livewireScripts
        <script>
            // Pusher.logToConsole = true;
            var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
                cluster: 'ap1'
            });
            var channel_general = pusher.subscribe('general');
            channel_general.bind('notification', function(data) {
                show_toast(data.message,'top-right',data.is_refresh);
            });
        </script>   
        @stack('after-scripts')
        @if (trim($__env->yieldContent('page-script')))
            <script>
                @yield('page-script')
            </script>
        @endif
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <script>
            function setNavbarShow(){
                $.ajax({
                    url: "{{route('set-navbar-show')}}",
                    }).done(function() {
                        
                    });
            }
            Livewire.on('message-success', (msg) => {
                $('.alert-success').show();
                $('.alert-success .message').html(msg);
                $('html, body').animate({
                    scrollTop: $("#wrapper").offset().top
                }, 0);
            });
            Livewire.on('message-error', (msg) => {
                $('.alert-error').show();
                $('.alert-error .message').html(msg);
                $('html, body').animate({
                    scrollTop: $("#wrapper").offset().top
                }, 0);
            });
            
            Livewire.on('error-message',(msg)=>{
                alert(msg);
            });
            Livewire.on('close-modal',()=>{
                $('.modal').modal('hide');
            });

            @if(Auth::check())
                @if(Auth::user()->user_access_id == 2)
                    $('body').addClass('layout-fullwidth');
                @endif
            @endif
            var counting_form_ = 0;
            Livewire.on('counting_form',()=>{
                counting_form_ = 0;
                console.log('counting form : '+counting_form_);
            });

            Livewire.on('go-to-div',(id)=>{
                go_to_div("#rekomendasi_anggota_"+id);
            });

            function go_to_div(target){
                console.log('target : '+target);
                if(counting_form_==0){
                    console.log('first target : '+target);
                    $('html, body').animate({
                        scrollTop: ($(target).offset().top-60)
                    }, 2000);
                    counting_form_++;
                }
            }
            
            function show_toast(message,positon,is_refresh=true)
            {
                if(is_refresh){
                    Livewire.emit('refresh');
                }
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                toastr['info'](message, '', {
                    positionClass: 'toast-'+positon
                });
            }

            $(".btn-toggle-offcanvas").on("click",function(){
		        $('body').removeClass('layout-fullwidth');
            });
        </script>
    </body>
</html>
