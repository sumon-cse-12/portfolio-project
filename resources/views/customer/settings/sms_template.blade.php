
<div class="form-group">
    <button class="btn btn-primary float-right mb-3 btn-sm" data-title="Add New Template" type="button" id="addNewTemplate">{{trans('customer.add_template')}}</button>
</div>
<div class="card-body table-responsive p-0 " style="height: 300px;">
    <table class="table table-head-fixed text-nowrap text-center">
        <thead>
        <tr>
            <th>{{trans('customer.title')}}</th>
            <th>{{trans('customer.status')}}</th>
            <th>{{trans('customer.action')}}</th>
        </tr>
        </thead>
        <tbody>
        @if($sms_templates->isNotEmpty())
        @foreach($sms_templates as $sms_template)
        <tr>
            <td>{{$sms_template->title}}</td>
            <td>@if($sms_template->status=='active')
            <span class="badge badge-success">{{$sms_template->status}}</span>
                @else
                    <span class="badge badge-danger">{{$sms_template->status}}</span>
                    @endif
            </td>
            <td><button type="button" data-value="{{json_encode($sms_template->only(['id','title','status','body']))}}" class="btn btn-sm btn-info template-edit">Edit</button>
                <button class="btn btn-sm btn-danger" type="button" data-message="Are you sure you want to delete this template?"
                        data-action="{{route('customer.sms.template.delete',['id'=>$sms_template->id])}}"
                data-input={"_method":"delete"}
                data-toggle="modal" data-target="#modal-confirm">Delete</button>
            </td>
        </tr>
        @endforeach
        @else
            <tr>
                <td></td>
                <td colspan="1">{{trans('customer.no_data_available')}}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

