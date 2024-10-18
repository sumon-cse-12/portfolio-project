@extends('layouts.auth_customer')

@section('title','Sign up')

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('auth.registration.title')</p>

        <form id="login_registration" action="{{route('signup')}}" method="post">
            @csrf
            @if(request()->get('plan'))
                <input type="hidden" name="plan_id" value="{{request()->get('plan')}}">
            @endif
            <div class="input-group mb-3">
                <input name="first_name" type="text" class="form-control"
                       placeholder="@lang('auth.registration.form.first_name')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="last_name" type="text" class="form-control"
                       placeholder="@lang('auth.registration.form.last_name')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="email" type="email" class="form-control"
                       placeholder="@lang('auth.registration.form.email')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input name="password" type="password" class="form-control"
                       placeholder="@lang('auth.registration.form.password')">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8 text-sm">
                    {!! trans('auth.terms_condition',['terms'=>'<a href="#">Terms and Condition</a>']) !!}
                </div>
                <!-- /.col -->
                <div class="col-4">
                 <div id="g_token_append">

                </div>
                    <button
                        type="{{get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:'') && isset(json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key)?'button':'submit'}}"
                        class="btn btn-primary btn-block signIn">{{trans('auth.form.button.sign_up')}}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <!-- /.social-auth-links -->
        <div class="row mt-3 d-none">
            <div class="col-6">
                <p class="mb-1">
                    <a href="{{route('password.request')}}">@lang('admin.forget_password')</a>
                </p>
            </div>
            <div class="col-6">
                <p class="mb-0">
                    <a href="{{route('login')}}" class="text-center">@lang('auth.form.sign_in')</a>
                </p>
            </div>
        </div>


    </div>

@endsection
@section('extra-script')
    @if(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:'') && isset(json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key))
        <script
            src="https://www.google.com/recaptcha/api.js?render={{json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key}}"></script>
        <script>
            $(document).on('click', '.signIn', function (e) {
                e.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute('{{json_decode(get_settings('recaptcha_key', isset($domain_seller_id)?$domain_seller_id:''))->recaptcha_site_key}}', {action: 'submit'}).then(function (token) {
                        // Add your logic to submit to your backend server here.
                        if (token) {
                            $('#g_token_append').html(`<input type="hidden" name="grecaptcha_response" value="${token}">`);
                            $('#login_registration').submit();
                        }
                    });
                });
            })
        </script>
    @endif

@endsection
