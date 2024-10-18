@extends('layouts.auth_admin')

@section('title') Admin Login @endsection

@section('extra-css')

@endsection

@section('content')
    <div class="card-body login-card-body">
        <p class="login-box-msg">@lang('admin.login_title')</p>

        <form action="{{route('admin.authenticate')}}" method="post">
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
                <button type="submit" class="btn btn-primary btn-block mb-2">@lang('admin.sign_in')</button>

                <!-- /.col -->
            </div>
        </form>

        <!-- /.social-auth-links -->

        <p class="mb-1 d-none">
            <a href="{{route('admin.password.request')}}">@lang('admin.forget_password')</a>
        </p>
    </div>

@endsection
@section('extra-script')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/readmore.min.js')}}"></script>


@endsection
