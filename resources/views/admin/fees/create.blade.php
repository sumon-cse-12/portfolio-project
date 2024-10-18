@extends('layouts.admin')

@section('service') {{ trans('admin.fees') }}   @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-service">{{ trans('admin.fees') }}</h2>
                </div>
                <form method="post" role="form" id="feesForm" action="{{route('admin.theme.fees.store')}}">
                    @csrf
                    <div class="card-body" >
                        <div class="" id="add_fees">
                            @php
                            $fees =json_decode(get_settings('fees'));
                            @endphp
                            <div class="form-group">
                                <label for="title">{{trans('admin.title')}}</label>
                                <input value="{{isset($fees)?$fees->title:''}}" type="text" name="title" class="form-control" placeholder="{{trans('admin.title')}}">
                            </div>
                            <div class="form-group">
                                <label for="sub_title">{{trans('admin.sub_title')}}</label>
                                <input value="{{isset($fees)?$fees->sub_title:''}}" type="text" name="sub_title" class="form-control" placeholder="{{trans('admin.sub_title')}}">
                            </div>
                            <div class="add-team text-right">
                                <button type="button"  class="btn btn-primary" id="add-input"><i class="fa fa-plus"></i></button>
                            </div>
                         @if(isset($fees) ? $fees->fees_data:'')
                         @foreach ($fees->fees_data as $fee) 
                          <div class="row input-fees" >
                                <div class="col-lg-12 text-right">
                                    <button type="button"  class="delete-fields btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="service">{{trans('admin.service')}}</label>
                                        <input value="{{isset($fee)?$fee->service:''}}" type="text" name="service[]" class="form-control" id="service"
                                               placeholder="{{trans('admin.service')}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="type_of_instrument">@lang('admin.short_description')</label>
                                        <input value="{{isset($fee)?$fee->type_of_instrument:''}}" type="text" name="type_of_instrument[]" class="form-control" id="type_of_instrument"
                                               placeholder="{{trans('admin.type_of_instrument')}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="uhn_rate">@lang('admin.uhn_rate')</label>
                                        <input value="{{isset($fee)?$fee->uhn_rate:''}}" type="text" name="uhn_rate[]" class="form-control" id="uhn_rate"
                                            placeholder="{{trans('admin.uhn_rate')}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="ea_rate">@lang('admin.ea_rate')</label>
                                        <input value="{{isset($fee)?$fee->ea_rate:''}}" type="text" name="ea_rate[]" class="form-control" id="ea_rate"
                                            placeholder="{{trans('admin.ea_rate')}}">
                                    </div>
                                </div>
                            </div>
                          @endforeach
                            @endif 

                        </div>
                        </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function() {
    $('#add-input').click(function(e) {
        e.preventDefault();
        var fieldsInput = `
        <div class="row input-fees">
                                <div class="col-lg-12 text-right">
                                    <button type="button" class="delete-fields btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="service">{{trans('admin.service')}}</label>
                                        <input type="text" name="service[]" class="form-control" id="service"
                                               placeholder="{{trans('admin.service')}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="type_of_instrument">@lang('admin.short_description')</label>
                                        <input type="text" name="type_of_instrument[]" class="form-control" id="type_of_instrument"
                                               placeholder="{{trans('admin.type_of_instrument')}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="uhn_rate">@lang('admin.uhn_rate')</label>
                                        <input type="text" name="uhn_rate[]" class="form-control" id="uhn_rate"
                                            placeholder="{{trans('admin.uhn_rate')}}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="ea_rate">@lang('admin.ea_rate')</label>
                                        <input  type="text" name="ea_rate[]" class="form-control" id="ea_rate"
                                            placeholder="{{trans('admin.ea_rate')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
        `;
                $('#add_fees').append(fieldsInput);
            });
    
            $(document).on('click', '.delete-fields', function(e) {
                $(this).closest('.input-fees').remove();
            });

});
    </script>
@endsection

