<div class="form-group row">
    <label for="inputEmail3" class="col-sm-3 col-form-label">Masking Name</label>
    <div class="col-sm-9">
        <input type="text" value="{{isset($sender_id)?$sender_id->sender_id:(old('sender_id'))}}" name="sender_id" class="form-control" id="inputEmail3"
               placeholder="Maximum 11 Character (No Special Characters) ">
    </div>
</div>

<div class="form-group row mt-3">
    <label class="col-sm-3 col-form-label">Your Name</label>
    <div class="col-sm-9">
        <input type="text" value="{{isset($detail)?$detail->name:(old('name'))}}" name="name" class="form-control" placeholder="Write you name as per NID card">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Designation</label>
    <div class="col-sm-9">
        <input type="text" name="designation" value="{{isset($detail)?$detail->designation:(old('designation'))}}" class="form-control" placeholder="Owner / Manager">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Company Name</label>
    <div class="col-sm-9">
        <input type="text" name="company_name" value="{{isset($detail)?$detail->company_name:(old('company_name'))}}" class="form-control" placeholder="Write your name as per TB licensed">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Email Address</label>
    <div class="col-sm-9">
        <input type="email" name="email" value="{{isset($detail)?$detail->email:old('email')}}" class="form-control" placeholder="example@demo.com">
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-3 col-form-label">Phone Number</label>
    <div class="col-sm-9">
        <input type="number" name="phone" value="{{isset($detail)?$detail->phone:(old('phone'))}}" class="form-control" placeholder="8801-XXXXX-XXXX">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Company Address</label>
    <div class="col-sm-9">
        <input type="text" name="company_address" value="{{isset($detail)?$detail->company_address:(old('company_address'))}}" class="form-control"
               placeholder="Company Address">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Company Description</label>
    <div class="col-sm-9">
        <textarea name="company_description" class="form-control" id="" cols="4" placeholder="Enter company description" rows="4">{!! isset($detail)?$detail->company_description:(old('company_description')) !!}</textarea>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-3 col-form-label">Current Aggregator</label>
    <div class="col-sm-9">
        <input type="text" name="aggregator" value="{{isset($detail)?$detail->aggregator:(old('aggregator'))}}" class="form-control"
               placeholder="If you have previously used from any aggregator">
    </div>
</div>
<div class="form-group row mt-2">
    <div class="col-md-6 col-sm-6">
        <label for="">{{trans('admin.logo')}}</label>
        <input type="file" name="logo" class="form-control">
    </div>
    <div class="col-md-6 col-sm-6">
        <label for="">{{trans('Signature')}}</label>
        <input type="file" class="form-control" name="signature">
    </div>
</div>

<div class="modal-footer p-2">
    <button id="modal-confirm-btn" type="submit" class="btn btn-primary btn-sm">@lang('customer.submit')</button>
</div>
