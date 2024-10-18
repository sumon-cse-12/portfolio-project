<div class="form-group">
    <label for="header_title">{{ trans('admin.header_title') }}</label>
    <input value="{{isset($contacts)?$contacts->header_title:old('header_title')}}" type="text" name="header_title" class="form-control" id="header_title"
           placeholder="{{ trans('admin.header_title') }}">
</div>
<div class="form-group">
    <label for="short_description">{{ trans('admin.short_description') }}</label>
    <textarea name="short_description" id="short_description" placeholder="{{ trans('admin.short_description') }}" class="form-control data">{{isset($contacts)?$contacts->short_description:''}}</textarea>
</div>
<div class="form-group">
    <label for="title">{{ trans('admin.title') }}</label>
    <input value="{{isset($contacts)?$contacts->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="{{ trans('admin.title') }}">
</div>
<div class="form-group">
    <label for="description">{{ trans('admin.description') }}</label>
    <textarea name="description" id="description" placeholder="{{ trans('admin.description') }}" class="form-control">{{isset($contacts)?$contacts->description:''}}</textarea>
</div>
<div class="form-group">
    <div class="d-flex align-tiem-center justify-content-between">
    <label for="features">Add Features</label>
    <a class="add-features add-btn btn btn-sm btn-primary" id="add-features"><i class="fa fa-plus" aria-hidden="true"></i></a>
</div>
    <div id="features-container">
        @if(isset($contacts)?$contacts->features:old('features'))
        @php
        $features = json_decode($contacts->features);
        @endphp
        @foreach($features as $feature)
        <div class="feature-input mt-3">
            <div class="row">
                <div class="col-lg-11">
                    <div class="form-group">
                        <input value="{{ $feature->features_title }}" type="text" name="features_title[]" class="form-control" placeholder="{{ trans('admin.title') }}" required>
                        </div>
                        <div class="form-group">
                        <textarea name="features_data[]" id="features" placeholder="{{ trans('admin.description') }}" class="form-control mt-3 data">{{ $feature->features_data }}</textarea>
                        </div>
                </div>
                <div class="col-lg-1">
                    <button class="delete-feature btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($contacts)?$contacts->status:''}}">
        <option {{isset($contacts) && $contacts->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($contacts) && $contacts->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>