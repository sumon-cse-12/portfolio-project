
<div class="form-group">
    <label for="blog_category">{{ trans('admin.blog') }} {{ trans('admin.category') }}</label>
    <select class="form-control" name="blog_category" id="blog_category">
        <option>{{ trans('admin.select') }} {{ trans('admin.blog') }} {{ trans('admin.category') }}</option>
        @foreach ($blogCategorys as $blogCategory)
        <option {{isset($bloglist) && $bloglist->blog_category==$blogCategory->id?'selected':''}} value="{{ $blogCategory->id }}">{{ $blogCategory->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="title">{{trans('admin.title')}}</label>
    <input type="text" class="form-control" name="title" value="{{ isset($bloglist)?$bloglist->title:'' }}" id="image" placeholder="{{ trans('admin.title') }}">
</div>
<div class="form-group">
    <label for="description">{{trans('admin.description')}}</label>
    <textarea name="description" id="description" cols="3" rows="3" class="form-control">{{isset($bloglist)?$bloglist->description:''}}</textarea>
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="blog_image" value="{{ isset($bloglist)?$bloglist->image:'' }}" id="image" placeholder="{{ trans('admin.image') }}">
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status">
        <option {{isset($bloglist) && $bloglist->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($bloglist) && $bloglist->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>
