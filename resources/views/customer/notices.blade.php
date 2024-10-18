@extends('layouts.customer')

@section('title')
    {{trans('admin.notices')}}
@endsection

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('admin.notices')}}</h2>
                        <div class="float-right">
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="sender_ids" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('admin.form.title')}}</th>
                                <th>{{trans('admin.table.description')}}</th>
                                <th>{{trans('admin.attached_data')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(getNotices() != 'null')
                                @foreach(getNotices() as $notice)
                                    <tr>
                                        <td>{{$notice->title}}</td>
                                        <td>
                                            {!! isset($notice->description)?clean($notice->description):'' !!}
                                        </td>
                                        <td class="text-center">
                                            @if(isset($notice->attached_data))
                                                <strong class="justify-content-center">
                                                    <a href="{{route('customer.download.notice.file', ['id'=>$notice->id])}}"><i class="fa fa-download downloadAttachedData mr-2 c-pointer" data-id="{{$notice->id}}"></i></a>
                                                </strong>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="3">{{trans('No Data Available')}}</td>
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('Set Price')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.sender.sender_id.setting')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{trans('admin.sender_id_price')}}</label>
                            <input type="number" name="sender_id_price"
                                   value="{{isset($senderIdPrice->value) && isset(json_decode($senderIdPrice->value)->sender_id_price)?json_decode($senderIdPrice->value)->sender_id_price:0}}"
                                   class="form-control" placeholder="Enter price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')

@endsection

