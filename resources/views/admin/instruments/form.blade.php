
<div class="form-group">
    <label for="title">{{ trans('admin.title') }}</label>
    <input value="{{isset($instruments)?$instruments->title:old('title')}}" type="text" name="title"
           class="form-control" id="title"
           placeholder="Title">
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="image" value="{{ isset($instruments)?$instruments->image:'' }}"
           id="image" placeholder=" {{ trans('admin.image') }}">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <textarea name="description" class="form-control summernote" id="description" cols="3" rows="3">{{isset($instruments)?$instruments->description:old('description')}}</textarea>
</div>


<div class="form-group">
    <div class="d-flex align-tiem-center justify-content-between">
        <label for="add-data">Add Table Data</label>
        <a class="add-data add-btn btn btn-sm btn-primary m-0" id="add-data"><i class="fa fa-plus pt-1"
                                                                                aria-hidden="true"></i></a>
    </div>
    <div id="data-container">

        @if(isset($instrumentDetails))
            @php
                $getIds=9878;
            @endphp
            @foreach($instrumentDetails as $instrumentDetail)
                @php $getIds++;@endphp
                <div class="card mt-2 delete_section_{{$getIds}}">
                    <div class="card-header">
                        <button type="button" data-id="{{$getIds}}"
                                class="delete-data btn btn-sm add-btn btn-danger float-right m-0">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="data-input card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <input type="text" name="table_title[{{$instrumentDetail->id}}]" value="{{$instrumentDetail->title}}"
                                       class="form-control mt-2" placeholder="Table Head" required>
                            </div>

                            @php
                                $keyTitles=json_decode($instrumentDetail->key, true);
                                $keyValues=json_decode($instrumentDetail->value, true);
                            @endphp

                            @if($keyTitles)
                                @foreach($keyTitles as $key=>$keyTitle)
                                    @php $getIds++; @endphp
                                    <div class="col-lg-12 section_new_ele_{{$instrumentDetail->id}}">
                                        <div class="row delete_rows_{{$getIds}}">
                                            <div class="col-lg-5">
                                                <input type="text" name="table_head[{{$instrumentDetail->id}}][]"
                                                       value="{{$keyTitle?$keyTitle:''}}"
                                                       class="form-control mt-2" placeholder="Enter Key" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" name="table_body[{{$instrumentDetail->id}}][]"
                                                       value="{{isset($keyValues[$key])?$keyValues[$key]:''}}"
                                                       class="form-control mt-2" placeholder="Enter Value" required>
                                            </div>
                                            @if($key==0)
                                                <div class="col-lg-1">
                                                    <button type="button" data-id="{{$instrumentDetail->id}}"
                                                            class="add_new_element btn btn-sm btn-primary float-right mt-3">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="col-lg-1">
                                                    <button type="button" data-id="{{$getIds}}"
                                                            class="del_new_element btn btn-sm btn-danger float-right mt-3">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                                @endif

                        </div>
                    </div>
                </div>

            @endforeach
        @endif
    </div>
</div>






