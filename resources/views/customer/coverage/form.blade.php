<div class="row p-3">
    <div class="col-md-12">
        <div class="form-group">
            <label for="">{{trans('Choose Country')}}</label>
            <select name="country" {{isset($coverage)?'disabled':''}} class="form-control country_select">
            @foreach($countries as $key=>$country)
                    <option
                        {{isset($coverage) && isset($coverage->country) && $coverage->country==$key?'selected':(old('country') && old('country')==$key?'selected':'')}}
                        value="{{$key}}">{{isset($country['name'])?ucfirst($country['name']):''}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('admin.send_sms')}}</label>
            <input type="number" name="plain_sms" class="form-control"
                   value="{{isset($coverage)?$coverage->plain_sms:(old('plain_sms')?old('plain_sms'):'')}}"
                   placeholder="{{trans('admin.enter_plain_sms')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('admin.receive_sms')}}</label>
            <input type="number" name="receive_sms" class="form-control"
                   value="{{isset($coverage)?$coverage->receive_sms:(old('receive_sms')?old('receive_sms'):'')}}"
                   placeholder="{{trans('admin.enter_receive_sms')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('Send MMS')}}</label>
            <input type="number" name="send_mms" class="form-control"
                   value="{{isset($coverage)?$coverage->send_mms:(old('send_mms')?old('send_mms'):'')}}"
                   placeholder="{{trans('Enter Send MMS')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('Receive MMS')}}</label>
            <input type="number" name="receive_mms" class="form-control"
                   value="{{isset($coverage)?$coverage->receive_mms:(old('receive_mms')?old('receive_mms'):'')}}"
                   placeholder="{{trans('Enter Receive MMS')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('Send Voice SMS')}}</label>
            <input type="number" name="send_voice_sms" class="form-control"
                   value="{{isset($coverage)?$coverage->send_voice_sms:(old('send_voice_sms')?old('send_voice_sms'):'')}}"
                   placeholder="{{trans('Enter Voice SMS')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('Receive Voice SMS')}}</label>
            <input type="number" name="receive_voice_sms" class="form-control"
                   value="{{isset($coverage)?$coverage->receive_voice_sms:(old('receive_voice_sms')?old('receive_voice_sms'):'')}}"
                   placeholder="{{trans('Enter Voice SMS')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('Send Whatsapp Message')}}</label>
            <input type="number" name="send_whatsapp_sms" class="form-control"
                   value="{{isset($coverage)?$coverage->send_whatsapp_sms:(old('send_whatsapp_sms')?old('send_whatsapp_sms'):'')}}"
                   placeholder="{{trans('Enter Whatsapp Send SMS')}}">
        </div>
    </div>

    <div class="col-md-6 mt-2">
        <div class="form-group">
            <label for="">{{trans('Receive Whatsapp Message')}}</label>
            <input type="number" name="receive_whatsapp_sms" class="form-control"
                   value="{{isset($coverage)?$coverage->receive_whatsapp_sms:(old('receive_whatsapp_sms')?old('receive_whatsapp_sms'):'')}}"
                   placeholder="{{trans('Enter Whatsapp Receive SMS')}}">
        </div>
    </div>
</div>
