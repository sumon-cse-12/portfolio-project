<div class="form-group">
    <label for="service_name">{{ trans('admin.service_name') }}</label>
    <input value="{{isset($fees)?$fees->service_name:''}}" type="text" name="service_name" class="form-control" id="service_name" placeholder="{{trans('admin.service_name')}}">
</div>
<div class="form-group">
    <label for="uhn_rate">{{ trans('admin.uhn_rate') }}</label>
    <input value="{{isset($fees)?$fees->uhn_rate:''}}" type="text" name="uhn_rate" class="form-control" id="uhn_rate" placeholder="{{trans('admin.uhn_rate')}}">
</div>
<div class="form-group">
    <label for="ea_rate">{{ trans('admin.ea_rate') }}</label>
    <input value="{{isset($fees)?$fees->ea_rate:''}}" type="text" name="ea_rate" class="form-control" id="ea_rate" placeholder="{{trans('admin.ea_rate')}}">
</div>
<div class="form-group">
    <label for="bottom_text">{{ trans('admin.bottom_text') }}</label>
    <input value="{{isset($fees)?$fees->bottom_text:''}}" type="text" name="bottom_text" class="form-control" id="bottom_text" placeholder="{{trans('admin.bottom_text')}}">
</div>
<div class="form-group">
    <label for="type_of_instrument">{{ trans('admin.type_of_instrument') }}</label>
    <select class="form-control" name="type_of_instrument" id="type_of_instrument" value="{{isset($type_of_instrument)?$type_of_instrument->status:''}}">
        <option>{{ trans('admin.select') }} {{ trans('admin.type_of_instrument') }}</option>
        @foreach ($instruments as $instrument)
        <option {{isset($fees) && $fees->type_of_instrument==$instrument->id?'selected':''}} value="{{ $instrument->id }}">{{ $instrument->title }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($fees)?$fees->status:''}}">
        <option {{isset($fees) && $fees->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($fees) && $fees->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>