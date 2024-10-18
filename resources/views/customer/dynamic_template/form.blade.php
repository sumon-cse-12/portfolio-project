<div class="form-group">
    <label for="">{{trans('customer.template_name')}}</label>
    <input type="text" class="form-control" name="name" value="{{isset($template)?$template->name:(old('name')?old('name'):'')}}">
</div>

<div class="form-group">
    <label for="">{{trans('customer.status')}}</label>
    <select name="status" class="form-control" >
        <option {{isset($template) && $template->status=='active'?'selected':''}} value="active">{{trans('customer.active')}}</option>
        <option {{isset($template) && $template->status=='inactive'?'selected':''}} value="inactive">{{trans('customer.inactive')}}</option>
    </select>
</div>

@if(isset($template))
    @php $counter=99999; @endphp
    @if(isset($fields) && $fields)
        @foreach($fields as $field)
            @php $counter++; @endphp
            <div class="row mt-2" id="field_row_{{$counter}}">
                <div class="col-md-11 col-11">
                    <label for="">Field</label>
                    <input type="text" name="inputes[]" value="{{$field}}" class="form-control"
                           placeholder="Enter Field Name">
                </div>
                <div class="col-md-1 col-1 pt-3">
                    <button class="btn btn-sm btn-danger mt-3 delete_field" data-id="{{$counter}}" type="button">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach
    @endif
@endif
<div class="row">
    <div class="col-md-11 col-11">
        <label for="">Field</label>
        <input type="text" name="inputes[]" class="form-control" placeholder="Enter Field Name">
    </div>
    <div class="col-md-1 col-1 pt-4">
        <button id="add_more" class="btn btn-sm btn-success mt-2" type="button">
            <i class="fa fa-plus"></i>
        </button>
    </div>
</div>

<div id="append_fields">

</div>


