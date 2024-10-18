<table class="w-100">
    <tr>
        <td><label>{{trans('customer.email_notification')}}</label></td>
        <td>
            <div class="form-group mt-2">
                <div class="custom-control custom-switch">
                    <input {{isset($customer_settings['email_notification']) && $customer_settings['email_notification']=='true'?'checked':''}} type="checkbox" class="custom-control-input" id="notification_switch">
                    <label class="custom-control-label" for="notification_switch"></label>
                </div>
            </div>
        </td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td><label for="">Webhook</label></td>
        <td>
            <select name="" id="webhook_type" class="form-control">
                <option {{isset($customer_settings['webhook']) && json_decode($customer_settings['webhook'])->type=='get'?'selected':''}} value="get">GET</option>
                <option {{isset($customer_settings['webhook']) && json_decode($customer_settings['webhook'])->type=='post'?'selected':''}} value="post">POST</option>
            </select>
        </td>
        <td>
            <input value="{{isset($customer_settings['webhook']) && json_decode($customer_settings['webhook'])->url?json_decode($customer_settings['webhook'])->url:''}}" type="text" id="webhook_url" required class="form-control" placeholder="Enter webhook url">
        </td>
        <td><button id="webhookSubmit" type="button" class="btn btn-primary ml-2">Save</button></td>
    </tr>

    <tr class="mt-3">
        <td><label for="">Data Posting</label></td>
        <td>
            <select name="" id="data_posting_type" class="form-control">
                <option {{isset($customer_settings['data_posting']) && json_decode($customer_settings['data_posting'])->type=='get'?'selected':''}} value="get">GET</option>
                <option {{isset($customer_settings['data_posting']) && json_decode($customer_settings['data_posting'])->type=='post'?'selected':''}} value="post">POST</option>
            </select>
        </td>
        <td>
            <input value="{{isset($customer_settings['data_posting']) && json_decode($customer_settings['data_posting'])->url?json_decode($customer_settings['data_posting'])->url:''}}" type="text" id="data_posting_url" required class="form-control" placeholder="Data Posting URL">
        </td>
        <td><button id="dataPostIngSubmit" type="button" class="btn btn-primary ml-2">Save</button></td>
    </tr>
    @if($verification)
    <tr class="mt-3">
        <td><label for="">Domain</label></td>
        <td>
            <div class="showStatus">
                @if(isset($domain))
                    @if($domain->status=='approved')
                    <button class="btn btn-sm btn-success disabled" disabled>{{ucfirst($domain->status)}}</button>
                    @else
                        <button class="btn btn-sm btn-danger disabled" disabled>{{ucfirst($domain->status)}}</button>
                    @endif
                @endif
            </div>
        </td>
        <td>
            <input value="{{isset($domain)?$domain->domain:''}}"
                   type="text" name="domain_name" required class="form-control" {{isset($domain)?'readonly':''}} placeholder="Enter your domain name with (www.)">
            <small class="text-danger error_ip">Domain should be pointed to {{isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:''}}</small>
        </td>
        <td>
            @if(!isset($domain) || $domain->status !='deleted')
                <button id="editDomain" type="button" class="btn btn-primary ml-2 {{isset($domain)?'':'d-none'}}">Edit
                </button>
                <button id="saveDomain" type="button" class="btn btn-primary ml-2 {{isset($domain)?'d-none':''}}">Save
                </button>

                @if(isset($domain))
                    <button class="btn  btn-danger" data-message="Are you sure you want to delete the domain ?"
                            data-action="{{route('customer.domain.delete')}}"
                            data-input={"status":"deleted","_method":"get"}
                            data-toggle="modal" data-target="#modal-confirm">Delete
                    </button>
                @endif
            @else
                <button type="button" class="btn btn-danger ml-2 disabled">Deleted</button>
            @endif
        </td>
    </tr>

        <tr>
            <td colspan="4">
                <div class="pt-4">
                    <strong>Description</strong> : {{trans('customer.domain_description')}}
                    <a target="_blank" class="btn btn-sm btn-primary ml-3 mt-3 float-right" href="https://toolbox.googleapps.com/apps/dig/#A/">Verify DNS</a>
                </div>
            </td>
        </tr>
    @endif
</table>

<!-- Modal -->
<div class="modal fade" id="editDomainModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Attention</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <h5>Are you sure to edit this domain ?</h5>
                    <span class="text-danger">If you edit this domain, current domain will be removed</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary confirmEdit">Confirm</button>
            </div>
        </div>
    </div>
</div>
