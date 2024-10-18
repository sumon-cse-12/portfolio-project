@extends('layouts.auth_customer')

@section('title','Login')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('admin.login_title')</p>
        <form id="login_form" action="{{route('authenticate')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input name="email" type="email" class="form-control" placeholder="@lang('admin.email')"
                       id="email">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="password" type="password" class="form-control"
                       placeholder="@lang('admin.password')" id="password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="icheck-primary">
                        <input name="remember_me" type="checkbox" id="remember">
                        <label for="remember">
                            @lang('admin.remember_me')
                        </label>
                    </div>
                </div>

                @if(env('APP_DEMO'))
                    <div class="col-lg-6">
                        <div class="icheck-primary">
                            <button type="button" class="btn btn-warning text-white btn-xs float-right mb-2"
                                    id="copy-btn">@lang('auth.login.form.copy')</button>
                        </div>
                    </div>
                @endif
                <div id="g_token_append">

                </div>
            <!-- /.col -->
                <button
                    type="{{get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:'') && isset(json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key)?'button':'submit'}}"
                    class="btn btn-primary btn-block signIn">{{trans('admin.login')}}</button>
            <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->
        <div class="row mt-3 d-none">
            <div class="col-8">
                <p class="mb-1">
                    <a href="{{route('password.request')}}">@lang('admin.forget_password')</a>
                </p>
            </div>
            @if ($registration_status=='enable')
                <div class="col-4">
                    <p class="mb-0">
                        <a href="{{route('pricing')}}" class="text-center">@lang('auth.form.registration')</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('extra-script')
    <script src="https://www.google.com/recaptcha/api.js"></script>
    @if(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:'') && isset(json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key))
    <script src="https://www.google.com/recaptcha/api.js?render={{json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key}}"></script>
    <script>
        $(document).on('click', '.signIn', function (e) {
            e.preventDefault();
            grecaptcha.ready(function () {
                grecaptcha.execute('{{json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key}}', {action: 'submit'}).then(function (token) {
                    // Add your logic to submit to your backend server here.
                    if (token) {
                        $('#g_token_append').html(`<input type="hidden" name="grecaptcha_response" value="${token}">`);
                        $('#login_form').submit();
                    }
                });
            });
        })
    </script>
    @endif
    <script>

        $("#copy-btn").click(function () {
            $("#email").val("client@demo.com");
            $("#password").val("123456");
        });
        localStorage.clear('noticeCounter');
    </script>
@endsection
