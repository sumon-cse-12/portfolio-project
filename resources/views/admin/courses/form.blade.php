<div class="form-group">
    <label for="title">{{ trans('admin.title') }}</label>
    <input value="{{isset($courses)?$courses->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="Title">
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="image" value="{{ isset($courses)?$courses->image:'' }}" id="image" placeholder=" {{ trans('admin.image') }}">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <input value="{{isset($courses)?$courses->description:old('description')}}" type="text" name="description" class="form-control" id="description"
           placeholder="description">
</div>
<div class="form-group">
    <label for="more_details">{{ trans('admin.more_details') }}</label>
    <textarea name="more_details" id="more_details" placeholder="more_details" class="form-control">{{isset($courses)?$courses->more_details:''}}</textarea>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($courses)?$courses->status:''}}">
        <option {{isset($courses) && $courses->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($courses) && $courses->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>