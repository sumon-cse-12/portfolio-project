<div class="form-group">
    <label for="name">{{ trans('admin.name') }}</label>
    <input value="{{isset($teams)?$teams->name:old('name')}}" type="text" name="name" class="form-control" id="name"
           placeholder="{{ trans('admin.name') }}">
</div>
<div class="form-group">
    <label for="email">{{ trans('admin.email') }}</label>
    <input value="{{isset($teams)?$teams->email:old('email')}}" type="email" name="email" class="form-control" id="email"
           placeholder="{{ trans('admin.email') }}">
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="image" value="{{ isset($teams)?$teams->image:'' }}" id="image" placeholder=" {{ trans('admin.image') }}">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <input value="{{isset($teams)?$teams->description:old('description')}}" type="text" name="description" class="form-control" id="description"
           placeholder="description">
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($teams)?$teams->status:''}}">
        <option {{isset($teams) && $teams->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($teams) && $teams->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>
