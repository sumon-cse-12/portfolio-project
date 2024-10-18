<div class="form-group">
    <label for="name">@lang('admin.page.name') *</label>
    <input value="{{isset($page) && $page->name?$page->name:old('name')}}" type="text" name="name"
           class="form-control" id="name"
           placeholder="@lang('admin.page.name')">
</div>
<div class="form-group">
    <label for="title">@lang('admin.page.title') *</label>
    <input value="{{isset($page) && $page->title?$page->title:old('title')}}" type="text" name="title"
           class="form-control" id="title"
           placeholder="@lang('admin.page.title')">
</div>
<div class="form-group">
    <label for="description">@lang('admin.page.description') *</label>
    <textarea name="description" id="description" class="form-control compose-body"
              placeholder="{{trans('admin.page.description')}}">{{isset($page) && $page->description?$page->description:old('description')}}</textarea>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="position">@lang('admin.page.position')</label>
            <select class="form-control" name="position" id="position">
                <option
                    {{isset($page) && $page->position=='header'?'selected':(old('status')=='header'?'selected':'')}} value="header">
                    Header
                </option>
                <option
                    {{isset($page) && $page->position=='footer'?'selected':(old('status')=='footer'?'selected':'')}} value="footer">
                    Footer
                </option>
            </select>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for="status">@lang('admin.page.status')</label>
            <select class="form-control" name="status" id="status">
                <option
                    {{isset($page) && $page->status=='published'?'selected':(old('status')=='published'?'selected':'')}} value="published">
                    Published
                </option>
                <option
                    {{isset($page) && $page->status=='unpublished'?'selected':(old('status')=='unpublished'?'selected':'')}} value="unpublished">
                    Unpublished
                </option>
            </select>
        </div>
    </div>
</div>
