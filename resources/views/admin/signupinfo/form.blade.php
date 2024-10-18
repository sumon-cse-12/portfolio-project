<div class="form-group">
    <label for="title">{{ trans('admin.title') }}</label>
    <input value="{{isset($sign_up_info)?$sign_up_info->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="Title">
</div>
<div class="form-group">
    <label for="short_description">{{ trans('admin.short_description') }}</label>
    <textarea name="short_description" id="short_description" placeholder="short_description" class="form-control features">{{isset($sign_up_info)?$sign_up_info->short_description:''}}</textarea>
</div>
<div class="form-group">
    <div class="d-flex align-tiem-center justify-content-between">
    <label for="features">Add Features</label>
    <a class="add-features add-btn btn btn-sm btn-primary" id="add-features"><i class="fa fa-plus" aria-hidden="true"></i></a>
</div>
    <div id="features-container">
        @if(isset($sign_up_info)?$sign_up_info->features:old('features'))
        @php
        $features = json_decode($sign_up_info->features);
        @endphp
            @foreach($features as $feature)
        <div class="feature-input mt-3">
            <textarea name="features[]" id="features" placeholder="features" class="form-control features">{{  $feature }}</textarea>
            <button class="delete-feature btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>
        </div>
        @endforeach
        @endif
    </div>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($sign_up_info)?$sign_up_info->status:''}}">
        <option {{isset($sign_up_info) && $sign_up_info->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($sign_up_info) && $sign_up_info->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>