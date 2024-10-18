<div class="form-group">
    <label for="title">Title</label>
    <input value="{{isset($services)?$services->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="Title">
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="image" value="{{ isset($services)?$services->image:'' }}" id="image" placeholder=" {{ trans('admin.image') }}">
</div>
<div class="form-group">
    <div class="d-flex align-tiem-center justify-content-between">
    <label for="features">Add Features</label>
    <a class="add-features add-btn btn btn-sm btn-primary" id="add-features"><i class="fa fa-plus" aria-hidden="true"></i></a>
</div>
    <div id="features-container">
        @if(isset($services)?$services->features:old('features'))
        @php
        $features = json_decode($services->features);
        @endphp
            @foreach($features as $feature)
        <div class="feature-input">
            <input value="{{$feature}}" type="text" name="features[]" class="form-control mt-2" placeholder="Features">
            <button class="delete-feature btn btn-sm add-btn btn-danger">x</button>
        </div>
        @endforeach
        @endif
    </div>
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($services)?$services->status:''}}">
        <option {{isset($services) && $services->status=='active'?'selected':''}} value="active">{{ trans('admin.active') }}</option>
        <option {{isset($services) && $services->status=='inactive'?'selected':''}} value="inactive">{{ trans('admin.inactive') }}</option>
    </select>
</div>