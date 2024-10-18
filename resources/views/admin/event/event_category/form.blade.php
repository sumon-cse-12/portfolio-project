<div class="form-group">
    <label for="name">{{ trans('admin.name') }}</label>
    <input value="{{isset($event_category)?$event_category->name:''}}" type="text" name="name" class="form-control" id="name" placeholder="{{trans('admin.name')}}">
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($event_category)?$event_category->status:''}}">
        <option {{isset($event_category) && $event_category->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($event_category) && $event_category->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>
