<div class="form-group">
    <label for="title">{{ trans('admin.title') }}</label>
    <input value="{{isset($questions)?$questions->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="Title">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <textarea name="description" id="description" placeholder="description" class="form-control">{{isset($questions)?$questions->description:''}}</textarea>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($questions)?$questions->status:''}}">
        <option {{isset($questions) && $questions->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($questions) && $questions->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>