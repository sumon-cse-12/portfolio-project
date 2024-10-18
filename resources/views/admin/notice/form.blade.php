<div class="form-group">
    <label for="title">@lang('admin.form.title')</label>
    <input value="{{isset($notice)?$notice->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="@lang('admin.form.input.title')">
</div>

<div class="form-group">
    <label for="price">{{trans('admin.table.description')}}</label>
    <textarea name="description" class="form-control" cols="4" rows="4">{!! isset($notice)?$notice->description:'' !!}</textarea>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="status">{{trans('customer.type')}}</label>
        <select class="form-control" name="type" id="is_reseller">
            <option {{isset($notice) && $notice->for=='all'?'selected':(old('type')=='all'?'selected':'')}} value="all">All</option>
            <option {{isset($notice) && $notice->for=='normal'?'selected':(old('type')=='normal'?'selected':'')}} value="normal">Customer</option>
            <option {{isset($notice) && $notice->for=='reseller'?'selected':(old('type')=='reseller'?'selected':'')}} value="reseller">Reseller</option>
            <option {{isset($notice) && $notice->for=='reseller_customer'?'selected':(old('type')=='reseller_customer'?'selected':'')}} value="reseller_customer">Reseller Customer</option>
        </select>
    </div>
    <div class="form-group col-md-6">
        <label for="status">@lang('admin.form.status')</label>
        <select class="form-control" name="status" id="status">
            <option {{isset($notice) && $notice->status=='active'?'selected':(old('status')=='active'?'selected':'')}} value="active">Active</option>
            <option {{isset($notice) && $notice->status=='inactive'?'selected':(old('status')=='inactive'?'selected':'')}} value="inactive">Inactive</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label for="">{{trans('admin.attached_data')}}</label>
    <input type="file" class="form-control p-1" name="attached_data">
</div>

</div>
