<div class="form-group">
    <label for="name">{{ trans('admin.name') }}</label>
    <input value="{{isset($category_publication)?$category_publication->name:''}}" type="text" name="name" class="form-control" id="name" placeholder="{{trans('admin.name')}}">
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($category_publication)?$category_publication->status:''}}">
        <option {{isset($category_publication) && $category_publication->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($category_publication) && $category_publication->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>
