@extends('layouts.admin')

@section('title',trans('admin.template'))

@section('extra-css')

<link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">


@endsection

@section('content')

    @php $template = json_decode(get_settings('template')); @endphp
    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card-body">


                    <div class="accordion" id="accordionExample">


                        <div class="card">
                            <div class="" id="headingOne">
                                <h2 class="mb-0 p-3">
                                    <button class="btn btn-link btn-block text-left" type="button"
                                            data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        Section Instruments
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    <form action="{{route('admin.theme.instruments')}}" method="post"
                                          enctype="multipart/form-data">
                                        @csrf

                                        @php $instruments= get_settings('instruments')?json_decode(get_settings('instruments')):[]; $instruments_key=87876;  @endphp

                                        <div class="form-group">
                                            <label for="">Section Title</label>
                                            <input type="text" class="form-control" name="main_title"
                                                   placeholder="Enter Title">
                                        </div>

                                        <div class="custom-s-card from-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button"
                                                            class="btn-sm btn-primary float-right section_Instruments">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            @if($instruments)
                                                @foreach($instruments as $key=>$instrument)
                                                    @php $instruments_key++;   @endphp
{{--                                                {{dd($instruments)}}--}}

                                                    <div class="row">
                                                        <input type="hidden" val="0" name="section_items[{{$key}}][]">
                                                        <div class="col-md-6">
                                                            <label for="">Title</label>
                                                            <input type="text"
                                                                   value="{{isset($instrument->title)?$instrument->title:''}}"
                                                                   name="title[0]" class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="">Image</label>
                                                            <input type="file" name="image[0]" class="form-control">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="">Description</label>
                                                            <textarea name="description[0]" cols="2" rows="2"
                                                                      class="form-control summernote">{{isset($instrument->descriptions)?$instrument->descriptions:''}}</textarea>
                                                        </div>

                                                        <div class="col-md-12 mt-2 sub_section_0">
                                                            <button type="button" data-id="0"
                                                                    class="text-right float-right btn-sm btn-primary sub_instruments">
                                                                <i class="fa fa-plus"></i>
                                                            </button>

                                                            <div class="card">
                                                                <div class="card-body">
                                                                    @php $instrument_item_titles=isset($instrument->item_titles)?json_decode($instrument->item_titles, true):[]; @endphp

                                                                    @if(isset($instrument_item_titles[0]))
                                                                        @foreach($instrument_item_titles[0] as $i_keys=>$item_titles)
                                                                            <div class="form-group">
                                                                                <label for="">Feature Title</label>
                                                                                <input type="text" class="form-control" value="{{$item_titles?$item_titles:''}}"
                                                                                       name="item_title[{{$key}}][{{$i_keys}}]">
                                                                            </div>

                                                                        @php $json_key_items=json_decode($instrument->key_items, true);  @endphp

                                                                            <div
                                                                                class="mt -3 form-group key_features_{{$key}}">
                                                                                @if(isset($json_key_items[$i_keys]) && $json_key_items[$i_keys])
                                                                                    @foreach($json_key_items[$i_keys] as $key_items)
                                                                                        <div class="row">
                                                                                            <div class="col-md-5">
                                                                                                <input type="text"
                                                                                                       name="key_feature_title[{{$i_keys}}][]"
                                                                                                       class="form-control" value="{{isset($key_items['key'])?$key_items['key']:''}}"
                                                                                                       placeholder="Enter title">
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                            <textarea name="key_feature_desc[{{$i_keys}}][]"
                                                                                      class="form-control" cols="2"
                                                                                      rows="2"
                                                                                      placeholder="Enter description">{{isset($key_items['desc'])?$key_items['desc']:''}}</textarea>
                                                                                            </div>
                                                                                            <div class="col-md-1">
                                                                                                <button data-id="{{$key}}"
                                                                                                        class="btn btn-primary add_key_features"
                                                                                                        type="button">
                                                                                                    <i class="fa fa-plus"></i>
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                @endif

                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                @endforeach
                                            @else
                                                <div class="row">
                                                    <input type="hidden" val="0" name="section_items[0][]">
                                                    <div class="col-md-6">
                                                        <label for="">Title</label>
                                                        <input type="text" name="title[0]" class="form-control">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="">Image</label>
                                                        <input type="file" name="image[0]" class="form-control">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="">Description</label>
                                                        <textarea name="description[0]" cols="2" rows="2"
                                                                  class="form-control summernote"></textarea>
                                                    </div>

                                                    <div class="col-md-12 mt-2 sub_section_0">
                                                        <button type="button" data-id="0"
                                                                class="text-right float-right btn-sm btn-primary sub_instruments">
                                                            <i class="fa fa-plus"></i>
                                                        </button>

                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="form-group">
                                                                    <label for="">Feature Title</label>
                                                                    <input type="text" class="form-control"
                                                                           name="item_title[0][1]">
                                                                </div>
                                                                <div class="mt -3 form-group key_features_0">
                                                                    <div class="row">
                                                                        <div class="col-md-5">
                                                                            <input type="text"
                                                                                   name="key_feature_title[1][]"
                                                                                   class="form-control"
                                                                                   placeholder="Enter title">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <textarea name="key_feature_desc[1][]"
                                                                                      class="form-control" cols="2"
                                                                                      rows="2"
                                                                                      placeholder="Enter description"></textarea>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <button data-id="0"
                                                                                    class="btn btn-primary add_key_features"
                                                                                    type="button">
                                                                                <i class="fa fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            @endif
                                        </div>


                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-success">
                                                Submit
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>


                        <div class="card mt-3">
                            <div class="" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                            data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                        Collapsible Group Item #2
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    Some placeholder content for the second accordion panel. This panel is hidden by
                                    default.
                                </div>
                            </div>
                        </div>


                        <div class="card mt-3">
                            <div class="" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button"
                                            data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                        Collapsible Group Item #3
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    And lastly, the placeholder content for the third and final accordion panel. This
                                    panel is hidden by default.
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <button class="btn btn-danger" type="button">
        <i class="fa fa-times"></i>
    </button>

@endsection

@section('extra-scripts')

@endsection

