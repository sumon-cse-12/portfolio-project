@extends('layouts.admin')

@section('title',trans('admin.customize_theme'))

@section('extra-css')
<style>
    .h-w-20{
        height: 20px;
        width: 20px;
    }
</style>
@endsection

@section('content')
    @php $themeData=json_decode(get_settings('theme_customize'))?json_decode(get_settings('theme_customize')):''; @endphp

    <section class="content">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12 col-12 mx-auto">
                <div class="card mt-3">
                    <form method="post" role="form" id="planForm" action="{{route('admin.theme.customize.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4>{{trans('admin.customize_theme')}}</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-group">
                                <label for="">Skin</label>
                                <div class="form-group">

                                    <input id="light" {{isset($themeData->type) && $themeData->type=='light'?'checked':''}} name="type" value="light" type="radio">
                                    <label for="light">Light</label>


                                    <input id="dark" name="type" {{isset($themeData->type) && $themeData->type=='dark'?'checked':''}} value="dark" class="ml-3" type="radio">
                                    <label for="dark">Dark</label>
                                </div>
                            </div>

                            <div id="forLightV">
                                <div class="form-group">
                                    <label for="">Navbar Color
                                        <span class="ml-4" style="background: {{isset($themeData->navbar_color)?$themeData->navbar_color:''}}; padding: 2px 16px"></span>
                                    </label>
                                    <input type="color" value="{{isset($themeData->navbar_color)?$themeData->navbar_color:''}}" name="navbar_color" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">Left Sidebar Color
                                        <span class="ml-4" style="background: {{isset($themeData->left_sidebar)?$themeData->left_sidebar:''}}; padding: 2px 16px"></span>
                                    </label>
                                    <input type="color" value="{{isset($themeData->left_sidebar)?$themeData->left_sidebar:''}}" name="left_sidebar" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="">Active Sidebar Color
                                        <span class="ml-4" style="background: {{isset($themeData->active_sidebar)?$themeData->active_sidebar:''}}; padding: 2px 16px"></span>
                                    </label>
                                    <input type="color" value="{{isset($themeData->active_sidebar)?$themeData->active_sidebar:''}}" name="active_sidebar" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="clSideBard" value="true" {{isset($themeData->collapse_sidebar) && $themeData->collapse_sidebar=='true'?'checked':''}} name="collapse_sidebar">
                                <label class="ml-2" for="clSideBard">Collapse Sidebar</label>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </div>
                    </form>
                </div>


            </div>
            <!-- /.card -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('extra-scripts')
<script>
    $(document).on('change', 'input[name=navbar_color]', function (e){
        const color = $(this).val();
        $('.main-header').css('background', color);
    })
    $(document).on('change', 'input[name=left_sidebar]', function (e){
        const color = $(this).val();
        $('.main-sidebar').css('background', color);
    });

    $(document).on('change', 'input[name=active_sidebar]', function (e){
        const color = $(this).val();
        $('.nav-link.active').css('background-color', color);
    });

    $(document).on('click', 'input[name=type]', function (e){
        const type = $(this).val();
        if(type=='dark'){
            $('html').attr('theme', 'dark-mode')
            $('#forLightV').addClass('d-none')
        }else{
            $('html').attr('theme', 'light-mode')
            $('#forLightV').removeClass('d-none')
        }
    });
    $(document).on('click', 'input[name=collapse_sidebar]', function (e){
        const check_type = $('input[name=collapse_sidebar]:checked').val();
        console.log(check_type)
        if(check_type) {
            $('body').addClass('collapse_sidebar')
        }else{
            $('body').removeClass('collapse_sidebar')
        }
        $('.fa-bars').trigger('click');

    });

</script>

@endsection

