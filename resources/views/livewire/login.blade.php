@section('title', 'Login')
<div class="vertical-align-wrap">
    <div class="vertical-align-middle auth-main">
        <div class="auth-box">

            <div class="top text-center d-block d-sm-none d-none d-sm-block d-md-none">
                <img src="{{get_setting('logo')}}" alt="{{get_setting('company')}}">
            </div>
            <div class="card">
                <div class="header">
                    <p class="lead">{{__('Login to your account')}}</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" id="form-login" method="POST" wire:submit.prevent="login" action="">
                        @if($message)
                        <p class="text-danger">{{$message}}</p>
                        @endif
                        <div class="form-group">
                            <label for="signin-username" class="sr-only control-label">{{ __('Username') }}</label>
                            <input type="text" class="form-control" id="signin-username" wire:model="username" placeholder="{{ __('Username') }}">
                            @error('username')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="signin-password" class="sr-only control-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="signin-password" wire:model="password" placeholder="{{ __('Password') }}">
                            @error('password')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="clearfix form-group">
                            <a href="{{route('register')}}" class="float-right">{{__('Register Now?')}}</a>
                        </div>

                        @if(env('APP_ENV')==='production')
                        <div wire:ignore>
                            <div class="g-recaptcha" data-callback="verifyCallback" data-sitekey="{{env('CAPTCHA_SITE_KEY')}}"></div>
                        </div>
                        @endif
                        <button type="submit" id="btn_submit" class="btn btn-primary btn-lg btn-block mt-1"><i class="fa fa-sign-in mr-2"></i>{{ __('LOGIN') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="position: absolute;bottom:0;">
            <p>Address : {{get_setting('address')}} | Phone : {{get_setting('phone')}} | Mobile : {{get_setting('mobile')}}</p>
        </div>
    </div>
    @if(env('APP_ENV')!=='local')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        var verifyCallback = function(response) {
            @this.set('token', response);
            $("#btn_submit").trigger('click');
        };
    </script>
    @endif
</div>