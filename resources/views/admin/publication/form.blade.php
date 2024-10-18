
<div class="form-group">
    <label for="publication_category">{{ trans('admin.publication_category') }}</label>
    <select class="form-control" name="publication_category" id="publication_category" value="{{isset($publication_category)?$publication_category->status:''}}">
        <option>{{ trans('admin.select_any') }}</option>
        @foreach ($category_publications as $category_publication)
        <option {{isset($publication) && $publication->publication_category==$category_publication->id?'selected':''}} value="{{ $category_publication->id }}">{{ $category_publication->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="description">{{trans('admin.title')}}</label>
    <input type="text" class="form-control" name="title" value="{{ isset($publication)?$publication->title:'' }}" id="image" placeholder="{{ trans('admin.title') }}">
</div>
<div class="form-group">
    <label for="description">{{trans('admin.description')}}</label>
    <textarea name="description" id="description" cols="3" rows="3" class="form-control summernote">{{isset($publication)?$publication->description:''}}</textarea>
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="publication_image" value="{{ isset($publication)?$publication->image:'' }}" id="image" placeholder="{{ trans('admin.image') }}">
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($publication_category)?$publication_category->status:''}}">
        <option {{isset($publication) && $publication->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($publication) && $publication->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>
