
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="nav h-100" id="vert-tabs-tab">

            <a class="nav-link active left-nav-link-plan" id="basic-info-nav">
                {{trans('admin.basic_info')}}
            </a>

            <a class="nav-link left-nav-link-plan" id="features-nav">
                {{trans('admin.features')}}
            </a>

            <a class="nav-link left-nav-link-plan" id="pricing-nav">
                {{trans('admin.pricing')}}
            </a>

            <a class="nav-link left-nav-link-plan" id="coverage-area-nav">
                {{trans('admin.coverage_area')}}
            </a>


            <a class="nav-link left-nav-link-plan" id="permission-nav">
                {{trans('admin.permission')}}
            </a>

        </div>
    </div>

    <div class="col-12 col-sm-12 mt-3">
        <div class="tab-content" id="vert-tabs-tabContent">
            <div class="tab-pane text-left fade show active tab_panel" id="basic-info" role="tabpanel"
                 aria-labelledby="vert-tabs-basic-info-tab">
                <div class="form-group">
                    <label for="">{{trans('admin.plan_title')}}</label>
                    <input type="text" name="title" class="form-control" placeholder="{{trans('Enter Plan Title')}}"
                           value="{{isset($plan)?$plan->title:(old('title')?old('title'):'')}}">
                </div>
                <div class="form-group">
                    <label for="">{{trans('Short Description')}}</label>
                    <input type="text" name="short_description" class="form-control"
                           placeholder="{{trans('Enter Plan Description')}}"
                           value="{{isset($plan)?$plan->short_description:(old('short_description')?old('short_description'):'')}}">
                </div>

                <div class="form-group">
                    <label for="">{{trans('Plan Price')}}</label>
                    <input type="number" name="price" class="form-control"
                           placeholder="{{trans('Enter Plan Price')}}"
                           value="{{isset($plan)?$plan->price:(old('price')?old('price'):'')}}">
                </div>

                <div class="form-group">
                    <label for="">{{trans('Recurring Type')}}</label>
                    <select class="form-control" name="recurring_type" id="recurring_type">
                        <option
                            {{isset($plan) && $plan->recurring_type=='weekly'?'selected':(old('recurring_type')=='weekly'?'selected':'')}} value="weekly">{{trans('admin.weekly')}}</option>
                        <option
                            {{isset($plan) && $plan->recurring_type=='monthly'?'selected':(old('recurring_type')=='monthly'?'selected':'')}} value="monthly">{{trans('admin.monthly')}}</option>
                        <option
                            {{isset($plan) && $plan->recurring_type=='yearly'?'selected':(old('recurring_type')=='yearly'?'selected':'')}} value="yearly">{{trans('admin.yearly')}}</option>
                        <option
                            {{isset($plan) && $plan->recurring_type=='custom'?'selected':(old('recurring_type')=='custom'?'selected':'')}} value="custom">{{trans('admin.custom')}}</option>
                    </select>
                </div>
                <div class="form-group {{isset($plan) && $plan->recurring_type=='custom'?'':'d-none'}}" id="customRecurring">
                    <label>Date range:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
                </span>
                        </div>
                        <input type="text" name="custom_date" value="{{isset($plan) && isset($date)?$date:''}}" class="form-control float-right" id="reservation">
                    </div>
                </div>

            </div>

            <div class="tab-pane fade tab_panel" id="features" role="tabpanel" aria-labelledby="vert-tabs-features-tab">
                <div class="form-group mb-0">
                    <label class="text-label">{{trans('SMS Sending Limit')}}*</label>
                    <div class="is-unlimited float-right">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label font-weight-bold">
                                    {{trans('Unlimited')}}
                                    <input data-name="sms_sending_limit"
                                           {{isset($plan) && $plan->unlimited_sms_send=='yes'?'checked':''}}
                                           name="unlimited_sms_send" type="checkbox" class="form-check-input isUnlimited"
                                           value="yes">
                                </label>
                            </div>
                        </div>
                    </div>
                    <input style="display: {{isset($plan) && $plan->unlimited_sms_send=='yes'?'none':'block'}}"
                           value="{{old('sms_sending_limit')?old('sms_sending_limit'):(isset($plan)?$plan->sms_sending_limit:0)}}"
                           type="number" name="sms_sending_limit"
                           class="form-control" placeholder="Ex: 5" required min="0" step="0.001">
                </div>

                <div class="form-group mb-0 pt-3">
                    <label class="text-label">{{trans('Maximum Contact Limit')}}*</label>
                    <div class="is-unlimited float-right">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label font-weight-bold">
                                    {{trans('Unlimited')}}
                                    <input data-name="max_contact"
                                           {{isset($plan) && $plan->unlimited_contact=='yes'?'checked':''}}
                                           name="unlimited_contact" type="checkbox" class="form-check-input isUnlimited"
                                           value="yes">
                                </label>
                            </div>
                        </div>
                    </div>
                    <input style="display: {{isset($plan) && $plan->unlimited_contact=='yes'?'none':'block'}}"
                           value="{{old('max_contact')?old('max_contact'):(isset($plan)?$plan->max_contact:0)}}"
                           type="number" name="max_contact"
                           class="form-control" placeholder="Ex: 5" required min="0" step="0.001">
                </div>

                <div class="form-group mb-0 pt-3">
                    <label class="text-label">{{trans('Contact Group Limit')}}*</label>
                    <div class="is-unlimited float-right">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label font-weight-bold">
                                    {{trans('Unlimited')}}
                                    <input data-name="contact_group_limit"
                                           {{isset($plan) && $plan->unlimited_contact_group=='yes'?'checked':''}}
                                           name="unlimited_contact_group" type="checkbox" class="form-check-input isUnlimited"
                                           value="yes">
                                </label>
                            </div>
                        </div>
                    </div>
                    <input style="display: {{isset($plan) && $plan->unlimited_contact_group=='yes'?'none':'block'}}"
                           value="{{old('contact_group_limit')?old('contact_group_limit'):(isset($plan)?$plan->contact_group_limit:0)}}"
                           type="number" name="contact_group_limit"
                           class="form-control" placeholder="Ex: 5" required min="0" step="0.001">
                </div>
            </div>

            <div class="tab-pane fade tab_panel" id="pricing" role="tabpanel" aria-labelledby="vert-tabs-pricing-tab">
                <div class="form-group">
                    <label for="">{{trans('SMS Unit Price')}}</label>
                    <input type="number" placeholder="{{trans('Enter SMS Unit Price')}}" name="sms_unit_price"
                           value="{{isset($plan)?$plan->sms_unit_price:(old('sms_unit_price')?old('sms_unit_price'):'')}}"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="">{{trans('Free SMS Credit')}}</label>
                    <input type="number" name="free_sms_credit" placeholder="{{trans('Enter Free SMS Credit')}}"
                           value="{{isset($plan)?$plan->free_sms_credit:(old('free_sms_credit')?old('free_sms_credit'):'')}}" class="form-control">
                </div>
            </div>


            <div class="tab-pane fade tab_panel" id="coverage-area" role="tabpanel" aria-labelledby="vert-tabs-coverage-area-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Select an coverage</label>
                            <select name="coverage[]" class="form-control coverage_select2">
                                @foreach($coverages as $coverage)
                                    @php $country_name=getCountryCode()[strtoupper($coverage->country)]['name']; @endphp
                                    <option value="{{$coverage->id}}">{{$country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade tab_panel" id="permission" role="tabpanel" aria-labelledby="vert-tabs-permission-tab">
                <div class="row">

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="">{{trans('API Availability')}}</label>
                            <select name="api_availability" class="form-control">
                                <option {{isset($plan) && $plan->api_availability=='no'?'selected':(old('api_availability') && old('api_availability')=='no'?'selected':'')}} value="no">{{trans('No')}}</option>
                                <option {{isset($plan) && $plan->api_availability=='yes'?'selected':(old('api_availability') && old('api_availability')=='yes'?'selected':'')}} value="yes">{{trans('Yes')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <label for="">{{trans('Sender ID Verification')}}</label>
                            <select name="sender_id_verification" class="form-control">
                                <option {{isset($plan) && $plan->sender_id_verification=='no'?'selected':(old('sender_id_verification') && old('sender_id_verification')=='no'?'selected':'')}} value="no">{{trans('No')}}</option>
                                <option {{isset($plan) && $plan->sender_id_verification=='yes'?'selected':(old('sender_id_verification') && old('sender_id_verification')=='yes'?'selected':'')}} value="yes">{{trans('Yes')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <div><span><strong>{{trans('admin.form.status')}}</strong></span>
                                <label class="switch ml-3">
                                    <input name="status" type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-2">
                        <div class="form-group">
                            <div><span><strong>{{trans('Set As Popular')}}</strong></span>
                                <label class="switch ml-3">
                                    <input name="set_as_popular" type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-2">
                <button type="button" class="btn btn-sm btn-danger" id="previous_btn">
                    Previeous
                </button>

                <button type="button" class="btn btn-sm btn-primary float-right" id="next_btn">
                    Click For Next Step
                </button>

                <button type="submit" class="btn btn-primary d-none submitBtn float-right">
                    @lang('admin.submit')
                </button>
            </div>
        </div>
    </div>
</div>
