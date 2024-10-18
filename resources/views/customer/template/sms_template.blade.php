@extends('layouts.customer')

@section('title') SMS Template @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.list')</h2>
                        <div class="float-right">
                            <button class="btn btn-primary float-right mb-3 btn-sm" data-title="Add New Template" type="button" id="addNewTemplate">{{trans('customer.add_template')}}</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
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
                                                <strong class="text-white bg-success px-2 py-1 rounded status-font-size">{{ucfirst($sms_template->status)}}</strong>
                                            @else
                                                <strong class="text-white bg-danger px-2 py-1 rounded status-font-size">{{ucfirst($sms_template->status)}}</strong>
                                            @endif
                                        </td>
                                        <td><button type="button" data-value="{{json_encode($sms_template->only(['id','title','status','body']))}}" class="btn btn-sm btn-info template-edit" title="Edit"><i class="fa fa-pencil-alt"></i></button>
                                            <button class="btn btn-sm btn-danger" type="button" data-message="Are you sure you want to delete this template?"
                                                    data-action="{{route('customer.sms.template.delete',['id'=>$sms_template->id])}}"
                                                    data-input={"_method":"delete"}
                                                    data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>
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
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="smsTemplateModal">
        <div class="modal-dialog">
            <form action="{{route('customer.sms.template')}}" method="post" id="templateForm">
                @csrf
                <input type="hidden" id="template_id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="title"></h4>
                        <button type="button" class="close" style="outline: white !important;" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{trans('customer.title')}}</label>
                            <input id="template_subject" value="{{old('title')?old('title'):''}}" type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label for="">{{trans('customer.status')}}</label>
                            <select id="template_status" name="status" class="form-control">
                                <option {{old('status') && old('status')=='active'?'selected':''}} value="active">{{trans('customer.active')}}</option>
                                <option {{old('status') && old('status')=='inactive'?'selected':''}} value="inactive">{{trans('customer.inactive')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">{{trans('customer.template_body')}}</label>
                            <textarea id="template_body" name="body" autofocus class="form-control" cols="5" rows="5">{{old('body')?old('body'):''}}</textarea>
                            <div class="text-right">
                                <b id="smsCount"></b> SMS (<b id="smsLength"></b>) Characters left
                            </div>
                        </div>
                        <div class="form-group">
                            @foreach(sms_template_variables() as $key=>$t)
                                <button type="button" data-name="{{$key}}" class="btn btn-sm btn-primary add_tool mt-2">{{ucfirst(str_replace('_',' ',$t))}}</button>
                            @endforeach
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('extra-scripts')
<script>
    $(document).on('click', '#addNewTemplate', function (e){
        $('#smsTemplateModal').modal('show');
        $('#title').text($(this).attr('data-title'));
    });

    $(document).on('click', '.template-edit', function (e) {
        $('#smsTemplateModal').modal('show');
        const value = JSON.parse($(this).attr('data-value'));

        $('#template_id').val(value.id);
        $('#template_subject').val(value.title);
        $('#template_body').val(value.body);
        $('#title').text('SMS Template Edit');
        $("#template_status").val(value.status);

    });


    $('.add_tool').on('click', function (e) {
        var curPos =
            document.getElementById("template_body").selectionStart;
        let x = $("#template_body").val();
        let text_to_insert = $(this).attr('data-name');
        $("#template_body").val(
            x.slice(0, curPos) + text_to_insert + x.slice(curPos));
    });
</script>

<script>
    (function($){
        $.fn.smsArea = function(options){

            //Generate Ascii Character Array
            var maxCh = 1000;
            var minCh = 0;
            var arrAscii = [];
            for(minCh =1;  minCh < maxCh; minCh++){
                arrAscii.push(minCh * 160);
            }
            //End

            //Generate Unicode Character Array
            var unMaxCh = 1000;
            var unMinCh = 0;
            var arrUnicode = [];
            for(unMinCh =1;  unMinCh < unMaxCh; unMinCh++){
                arrUnicode.push(unMinCh * 70);
            }
            //End

            var
                e = this,
                cutStrLength = 0,

                s = $.extend({

                    cut: true,
                    maxSmsNum: 1000,
                    interval: 5,

                    counters: {
                        message: $('#smsCount'),
                        character: $('#smsLength')
                    },

                    lengths: {
                        ascii: arrAscii,
                        unicode: arrUnicode
                    }
                }, options);


            e.keyup(function(){

                clearTimeout(this.timeout);
                this.timeout = setTimeout(function(){

                    var
                        smsType,
                        smsLength = 0,
                        smsCount = -1,
                        charsLeft = 0,
                        text = e.val(),
                        isUnicode = false;

                    for(var charPos = 0; charPos < text.length; charPos++){
                        switch(text[charPos]){
                            case "\n":
                            case "[":
                            case "]":
                            case "\\":
                            case "^":
                            case "{":
                            case "}":
                            case "|":
                            case "€":
                                smsLength += 2;
                                break;

                            default:
                                smsLength += 1;
                        }

                        //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
                        if(text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                            isUnicode = true;
                    }

                    if(isUnicode)   smsType = s.lengths.unicode;
                    else                smsType = s.lengths.ascii;

                    for(var sCount = 0; sCount < s.maxSmsNum; sCount++){

                        cutStrLength = smsType[sCount];
                        if(smsLength <= smsType[sCount]){

                            smsCount = sCount + 1;
                            charsLeft = smsType[sCount] - smsLength;
                            break
                        }
                        console.log(sCount, s.maxSmsNum);
                    }

                    if(s.cut) e.val(text.substring(0, cutStrLength));
                    smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

                    s.counters.message.html(smsCount);
                    s.counters.character.html(charsLeft);

                }, s.interval)
            }).keyup();

        }}(jQuery));


    //Start
    $(function(){
        $('#template_body').smsArea();
    })
</script>
@endsection


