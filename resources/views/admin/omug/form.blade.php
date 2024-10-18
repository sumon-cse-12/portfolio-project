<div class="form-group">
    <label for="title">{{ trans('admin.title') }}</label>
    <input value="{{isset($omug)?$omug->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="{{ trans('admin.title') }}">
</div>
<div class="form-group">
    <label for="short_description">{{ trans('admin.short_description') }}</label>
    <textarea name="short_description" id="description" placeholder="description" class="form-control description">{{isset($omug)?$omug->short_description:''}}</textarea>
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="image" value="{{ isset($omug)?$omug->image:'' }}" id="image" placeholder=" {{ trans('admin.image') }}">
</div>
<div class="form-group">
    <label for="video_link">{{ trans('admin.video_link') }}</label>
    <input value="{{isset($omug)?$omug->video_link:old('video_link')}}" type="text" name="video_link" class="form-control" id="video_link"
           placeholder="{{ trans('admin.video_link') }}">
</div>
<div class="form-group">
    <label for="image_link">{{ trans('admin.image_link') }}</label>
    <input value="{{isset($omug)?$omug->video_link:old('image_link')}}" type="text" name="image_link" class="form-control" id="image_link"
           placeholder="{{ trans('admin.image_link') }}">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <textarea name="description" id="description" placeholder="description" class="form-control description">{{isset($omug)?$omug->description:''}}</textarea>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($omug)?$omug->status:''}}">
        <option {{isset($omug) && $omug->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($omug) && $omug->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>