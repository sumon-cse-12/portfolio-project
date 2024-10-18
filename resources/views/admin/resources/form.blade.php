<div class="form-group">
    <label for="header_title">{{ trans('admin.header_title') }}</label>
    <input value="{{isset($resources)?$resources->header_title:old('header_title')}}" type="text" name="header_title" class="form-control" id="header_title"
           placeholder="{{ trans('admin.header_title') }}">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <textarea name="description" id="description" placeholder="{{ trans('admin.description') }}" class="form-control">{{isset($resources)?$resources->description:''}}</textarea>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($resources)?$resources->status:''}}">
        <option {{isset($resources) && $resources->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($resources) && $resources->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>
