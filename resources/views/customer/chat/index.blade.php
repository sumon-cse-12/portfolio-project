@extends('layouts.customer')

@section('title') Chats @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <style>
        .search-box {
            background: #fafafa;
            padding: 0px 0px;
            border-radius: 14px;
        }

        .input-wrapper {
            padding: 13px;
            background: #eee;
            border-radius: 20px;
        }

        i {
            vertical-align: middle;
        }

        input {
            border: none;
            border-radius: 30px;
            width: 80%;
            height: 35px;
            background: #eeeeee;
        }

        ::placeholder {
            font-weight: 300;
            margin-left: 20px;
        }

        :focus {
            outline: none;
        }

        .friend-drawer {
            display: flex;
            vertical-align: baseline;
            background: #fff;
            transition: .3s ease;
        }

        --grey {
            background: #eee;
        }

        .text {
            margin-left: 12px;
            width: 100%;
        }


        p {
            margin: 0;
        }

        --onhover:hover {
            background: blue;
            cursor: pointer;
        }

        hr {
            margin: 5px auto;
            width: 60%;
        }

        .chat-bubble--left {
            padding: 10px 14px;
            background: #D9FDD3;
            margin: 10px;
            border-radius: 9px;
            position: relative;

        :after {
            content: '';
            position: absolute;
            top: 50%;
            width: 0;
            height: 0;
            border: 20px solid transparent;
            border-bottom: 0;
            margin-top: -10px;
        }

        --left {

        :after {
            left: 0;
            border-right-color: #eee;
            border-left: 0;
            margin-left: -20px;
        }

        }
        --right {

        :after {
            right: 0;
            border-left-color: blue;
            border-right: 0;
            margin-right: -20px;
        }

        }
        }
        .chat-bubble--right {
            padding: 10px 14px;
            background: #d3d7de;
            margin: 10px;
            border-radius: 9px;
            position: relative;
        }

        .offset-md-9 {

        .chat-bubble {
            background: blue;
            color: #fff;
        }

        }
        .chat-box-tray {
            background: #eee;
            display: flex;
            align-items: baseline;
            padding: 10px 15px;
            align-items: center;
            margin-top: 65px;
            bottom: 0;

        input {
            margin: 0 10px;
            padding: 6px 2px;
        }

        i {
            color: grey;
            font-size: 30px;
            vertical-align: middle;

        :last-of-type {
            margin-left: 25px;
        }

        }
        }
        .chat-box-body {
            min-height: 360px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .chat-box-side {
            height:  calc(86vh - 212px);
            overflow-y: auto;
        }

        .icon {
            display: none;
            color: black;
        }

        @media (max-width: 700px) {
            .icon {
                display: block !important;
            }
        }

        .selectBox {
            height: 40px;
            border: 0;
            margin-right: 16px;
            border-radius: 10px;
            padding: 5px;
        }

        .label {
            width: 100% !important;
        }

        .list-group-item-light.list-group-item-action.active {
            background-color: #3c8dbc;
            border-color: #3c8dbc;
        }

        .loading {
            position: relative;
            top: 50%;
            right: 0;
            bottom: 0;
            left: 0;
            background: #fff;
        }

        .loader {
            left: 50%;
            margin-left: -4em;
            font-size: 10px;
            border: .8em solid rgba(218, 219, 223, 1);
            border-left: .8em solid rgba(58, 166, 165, 1);
            animation: spin 1.1s infinite linear;
        }

        .loader, .loader:after {
            border-radius: 50%;
            width: 4em;
            height: 4em;
            display: block;
            position: absolute;
            top: 50%;
            margin-top: -4.05em;
        }

        @keyframes spin {
            0% {
                transform: rotate(360deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }


        .message-sent-time {
            color: black;
            float: right;
            margin: -10px 11px 0px 0px;
            font-size: 12px;
        }

        .chat_heading {
            padding: 0px !important;
        }

        .response_value {
            padding: 10px 0px 10px 20px;
            cursor: pointer;
            color: black !important;
            border-bottom: 0.5px solid #e0e2e6;
        }

        #showResponse {
            width: 100%;
            position: absolute;
            z-index: 99;
            background: #f4f6f9;
            color: black;
            bottom: 47px;
            min-height: 100px;
            max-height: 250px;
            overflow-y: auto;
            padding: 10px 0px 10px 0px;
            margin: 0 auto;
            border-radius: 8px;
        }

        .type_message_section {
            width: 100%;
        }

        .card {
            border-radius: 14px;
        }

        .inbox-heading {
            padding: 10px 0px 4px 15px;
        }

        .card-body {
            padding: 0px 10px !important;
        }

        .daterangepicker.show-calendar {
            bottom: auto !important;
            top: 28% !important;
            left: 15% !important;
        }

        .search-chat-head {
            border-bottom: hidden !important;
            background: white !important;
        }

        .search-chat-head:hover {
            border-bottom: hidden !important;
            background: white !important;
        }

        .search-section .input-group-text {
            border-radius: 15px 0px 0px 14px;
        }

        .search-section input {
            border-radius: 0px 15px 15px 0px;
        }

        #filterBy {
            border-radius: 15px;
        }

        #sortBy {
            border-radius: 15px;
        }

        #dropdownMenuButton {
            font-size: 13px !important;
            padding: 9px !important;
        }

        .filter-section {
            list-style-type: none;
        }

        .chat-number-section .date-section {
            padding: 7px;
            border-radius: 18px;
            background: #eeeeeeb8;
        }

        .chat-number-section .number-section {
            padding: 7px;
            border-radius: 18px;
            background: #eeeeeeb8;
        }

        .c-pointer {
            cursor: pointer;
        }

        .chat-number-section {
            overflow-x: hidden;
        }

        .exception_modal_btn {
            border-radius: 20px;
        }

        .grade-list {
            max-height: 200px;
            overflow-y: scroll;
        }

        .paginate-icon {
            font-size: 22px !important;
        }

        #search {
            padding: 5px 3px 5px 5px !important;
        }

        #gradeList {
            max-height: 200px;
            overflow-y: scroll;
        }

        .settings-tray, .find-contact-section {
            /*border-bottom: 1px solid grey;*/
            box-shadow: 0 4px 2px -2px #eee;
        }

        .btn-danger {
            background-color: #f51026 !important;
            border-color: #f51026 !important;
        }

        #addException {
            color: black !important;
            background-color: yellow !important;
            border-color: yellow !important;
        }

        .ajax-loader {
            min-width: 100%;
            min-height: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
        }


        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background-color: #ebebeb;
            -webkit-border-radius: 10px;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            -webkit-border-radius: 10px;
            border-radius: 10px;
            background: #bfbfbf;
        }
    </style>
    <style>
        .input-group-text {
            padding: 0px !important;
            margin: 0px !important;
        }

        .section-new-message .msg-body-section {
            padding: 7px;
            border-radius: 5px;
            box-shadow: 0 11px 14px rgb(0 0 0 / 3%), 0 6px 7px rgb(0 0 0 / 7%);
            border: 1px solid #0769fb87;
        }

        .msg-body-section .type_message {
            border: hidden !important;
        }

        .msg-body-section .input-group-text {
            background: white !important;
            border: hidden !important;
        }

        #page-content-wrapper {
            min-height: 485px !important;
        }

        .section-new-message {
            position: relative;
            bottom: 0;
            width: 100%;
        }


        .attact-icon .choose_file {
            padding: 3px;
            border-radius: 50px;
            margin-right: 6px;
            cursor: pointer;
            float: right;
        }

        .msg-send-section .sent-btn small {
            font-weight: 700 !important;
        }

        .msg-send-section .sent-btn {
            margin-right: 12px;
            margin-left: -3px;
            background: var(--primary) !important;
            border: 1px solid #f3f4f4;
            box-shadow: 0 11px 14px rgb(0 0 0 / 3%), 0 6px 7px rgb(0 0 0 / 7%);
        }

        .fa-smile-o:before {
            content: "\f118";
        }

        .emoji-picker-icon {
            margin-top: 5px !important;
            right: 42px !important;
        }

        .empty-details-section {
            height: calc(100vh - 230px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat.active h6 {
            font-weight: 600 !important;
        }

        .emoji-menu {
            bottom: 46px !important;
        }

        .message-body-section .con-name {
            font-weight: 500;
            margin-bottom: 3px !important;
        }

        .btn-p-custom {
            background: var(--primary) !important;
            border-radius: 16px;
            color: white !important;
            box-shadow: 0 2px 4px rgb(0 0 0 / 14%), 0 3px 4px rgb(0 0 0 / 12%);
            padding: 10px !important;
        }

        #searchContact {
            border: hidden !important;
        }

        body {
            overflow-x: hidden !important;
        }

        .start-chat-text {
            display: inline-block;
            margin-left: 5px;
        }

        /*.type_message {*/
        /*    cursor: text;*/
        /*}*/

        .section-new-message {
            display: none;
        }

        #allContacts {
            height: 100vh;
            overflow-y: auto;
        }

        .new-chat-section {
            height: calc(100vh - 130px);
            overflow: hidden;
        }

        .failed-message {
            color: red;
            font-weight: 500;
            background: rgba(255, 0, 0, .03);
        }

        .chat-from-action {
            display: none;
            border-radius: 50%;
            height: 23px;
            width: 23px;
            position: absolute;
            top: 0;
        }

        .chat-from-action:hover {
            background: rgba(0, 0, 0, .1);
        }
        .chat:hover .chat-from-action{
            display: inline-block;
        }


        .chat-reply-action {
            display: none;
            border-radius: 5px;
            position: absolute;
        }


        .chat-bubble--left:hover .chat-reply-action{
            display: inline-block;
        }
        .left-bubble-title{
            margin: 10px 0;
        }

        .left-side-bubble-icon{
            font-size: 18px;
            background: var(--primary);
            padding: 8px 12px;
            border-radius: 50px;
            color: white;
            position: relative;
            top: 7px;
            }

        .unread-msg-counter{
            float: right;
            font-weight: 900;
            background: var(--primary);
            padding: 0px 6px;
            border-radius: 25px;
            color: white;
        }
        .empty-image-section img{
            width:100%;
            height:100%;
        }
        .empty-image-section{
            width: 200px;
        }
        .from-nmbr-border-right{
            border-right: 2px solid var(--primary) !important;
            z-index: 99999;
        }
        /*.sent-btn i{*/
        /*    color: white !important;*/
        /*}*/
        .sent-btn{
            background: var(--primary)!important;
            color: white !important;
            width: 100%;
            height: 100%;
        }
        .newMessage{
            position: relative;
            z-index: 9999;
            background: var(--primary);
            width: 77%;
            text-align: left;
            left: 10%;
            padding: 5px 25px;
            border-radius: 15px;
            color: white;
            cursor: pointer;
        }
    </style>
    <link href="{{asset('emoji/css/emoji.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid" id="wrapper">
        <div class="row">
            <div class="col-md-5 col-xl-5 col-sm-5">
                <div class="card p-2 mt-3">
                    <div class="card-heading message-title-section">
                        <div class="inbox-heading">
                            <h6><i class="fa fa-inbox mr-2"></i> <b>Messages</b></h6>
                            <button class="btn  btn-p-custom new_chat mt-2" type="button">
                                <i class="fa fa-sms text-white"></i> <span class="start-chat-text">Start Chat </span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="border-end bg-white" id="sidebar-wrapper">
                            <div class="list-group list-group-flush">
                                <div class="list-group list-group-flush">
                                    <div
                                        class="list-group-item list-group-item-action search-chat-head list-group-item-light chat_heading">

                                    </div>
                                    <div class="ajax-loader d-none">
                                        <img src="{{asset('images/ezgif-1-7aba96d47e.gif')}}" alt="">

                                    </div>
                                    <div class="newMessageAlert">

                                    </div>
                                    <div class="chat-box-side mt-3 chat-number-section" id="numb" data-current-page="2">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center pt-3">
                                <button class="btn btn-sm mr-3 disabled" id="previous-page"><i
                                        class="fa fa-chevron-left paginate-icon text-success"></i></button>
                                <span>Pages <span class="ml-2" id="cPage">1</span></span>
                                <button class="btn btn-sm ml-3" id="next-page"><i
                                        class="fa fa-chevron-right paginate-icon text-success"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-xl-7 col-sm-7">
                <!-- Page content wrapper-->
                <div class="card p-2 mt-3 message-details-section">
                    <div id="page-content-wrapper" style="width: 100%;">
                        <div class="container-fluid">
                            <div class="col-md-12 d-none details-section">
                                <div class="settings-tray">
                                    <div class="friend-drawer no-gutters friend-drawer--grey">
                                        <div class="text">
                                            <p class="text-muted mt-2" id="mess"></p>
                                            <div class="form-group label pt-2 d-none" id="label">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <button class="btn exception_modal_btn btn-warning btn-sm"
                                                                data-type="add" id="addException" type="button">Suppress
                                                        </button>
                                                        <button class="btn exception_modal_btn btn-danger btn-sm d-none"
                                                                data-type="delete" id="removeException" type="button">
                                                            Suppress
                                                        </button>
                                                        <div style="display: initial">
                                                            <button class="btn btn-sm btn-success ml-3"
                                                                    id="sendNumberInfo">Push
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 text-center" id="contactName">

                                                    </div>
                                                    <div class="col-lg-3">
                                                        <select class="form-control label" name="label"
                                                                id="select-label">
                                                            @foreach($labels as $label)
                                                                <option
                                                                    style="color: {{isset($label->color)?$label->color:''}}"
                                                                    value="{{$label->id}}">{{ucfirst($label->title)}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <a type="button"
                                                           class="btn btn-info save d-none text-white">@lang('admin.form.button.save')</a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-box-body" id="to-chat" data-current-chats="2"></div>
                            </div>
                            <div class="col-md-12 empty-details-section text-center">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="empty-text">
                                            <div class="empty-image-section mx-auto">
                                                <img src="{{asset('images/campaign-cuate.png')}}" alt="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <h6 class="text-center empty-text">{{formatDate(now())}}
                                            <br>
                                            <span class="mt-4 pt-3  font-weight-bold">Select a contact to start conversation</span>
                                        </h6>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12 new-chat-section d-none text-center">
                                <div class="row mt-4 find-contact-section">
                                    <div class="col-md-2 col-4 pt-2">
                                        <label for="">To :</label>
                                    </div>
                                    <div class="col-md-10 col-10">
                                        {{--                                        <div id="searchContact" data-placeholder="Enter name" contenteditable style="">--}}

                                        {{--                                        </div>--}}
                                        <input type="text" id="searchContact" placeholder="Type a name to find contact"
                                               class="form-control b-0">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12 col-12" id="allContacts">
                                        @foreach(auth('customer')->user()->contacts()->limit(30)->get() as $contact)
                                            @php
                                                $chars = 'ABCDEF0123456789';
                                                  $color = '#';
                                                  for ( $i = 0; $i < 6; $i++ ) {
                                                     $color .= $chars[rand(0, strlen($chars) - 1)];
                                                  }
                                            @endphp
                                            <div data-to-number="{{$contact->number}}"
                                                 class="chat row single-contact p-2 mt-1" id="c_{{$contact->id}}"
                                                 data-number="{{$contact->number}}"
                                                 data-name="{{$contact->fullname?:$contact->number}}"
                                                 data-id="{{$contact->id}}">
                                                <div class="col-md-2 col-2 text-center new-contact-section"
                                                     id="contact_name_{{$contact->id}}">
                                                    <strong style="background: {{$chars}}" class="new-c-name-icon">
                                                        {{$contact->first_name?substr(strtoupper($contact->first_name), 0, 1):'?'}}
                                                    </strong>
                                                </div>
                                                <div class="col-md-9 text-left">
                                                    <h6 class="m-0"> {{$contact->fullname}}</h6>
                                                    <h6 class="m-0">{{$contact->number}}</h6>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 section-new-message">
                                <div class="col-11 col-sm-10 msg-body-section">
                                    <div class="input-group lead emoji-picker-container">
                                        <div class="input-group-prepend from-nmbr-border-right">
                                               <span class="input-group-text">
                                                   <div class="btn-group">
                                                       <button type="button"
                                                               class="btn pt-0 pb-0 from-number-text dropdown-toggle"
                                                               data-toggle="dropdown" aria-expanded="false">
                                                           {{isset($numbers[0])?substr($numbers[0]->number, 0,5):'From'}}
                                                       </button>
                                                       <div class="dropdown-menu" role="menu" style="">
                                                              @foreach($numbers as $number)
                                                               <a class="dropdown-item choose-from-number"
                                                                  data-number="{{$number->number}}"
                                                                  href="#">{{$number->number}}</a>
                                                           @endforeach
                                                       </div>
                                                   </div>
                                               </span>
                                        </div>


                                        @if($chat_responses->isNotEmpty())
                                            <div id="showResponse" class="d-none">
                                                @foreach($chat_responses as $chat_response)
                                                    <h6 data-title="{{isset($chat_response->content)?$chat_response->content:''}}"
                                                        class="response_value">{{$chat_response->title}}</h6>
                                                @endforeach
                                            </div>
                                        @endif


                                        <input type="text" data-emojiable="true" data-emoji-input="unicode"
                                               class="form-control pt-2 type_message" id="message"
                                               placeholder="Type your message here...">
                                        <div class="attact-icon">
                                            <span class="choose_file"><i class="fa fa-link"></i></span>
                                        </div>

                                        <input type="hidden" id="number"
                                               value="{{isset($numbers[0])?$numbers[0]->number:''}}"
                                               class="setFromNumber">
                                        <input type="file" name="mms_file" class="mms_file d-none">
                                        <input type="hidden" name="chat_type" value="existing">

                                    </div>
                                </div>
                                <div class="col-l col-sm-2 msg-send-section">
                                    <button class="btn btn-default sent-btn send">
                                        <small>Send</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="exceptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exception</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark exception_message"></p>
                    <div class="d-none form-group mt-3" id="check_new_contact">
                        <input type="checkbox" class="float-left" id="checkInput" name="check_new_contact"
                               style="width: 5% !important;"> <label for="checkInput" class="ml-2 mt-2">Do you want to
                            add this number in your contact list?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="saveException" class="btn btn-primary">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="active_number">

    <div class="modal" id="addNewContact" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-dark">Do you want to add this number in your contact list?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addNewContactBtn" class="btn btn-primary">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @php $settings = auth('customer')->user()->settings->where('name', 'data_posting')->first();
     $settings = isset($settings) && isset($settings->value)?json_decode($settings->value):'';
    @endphp
    <!-- Modal -->
    <div class="modal fade" id="sendCInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Send</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Select Method</label>
                        <select name="" class="form-control" id="sendUrlMethod">
                            <option {{isset($settings->type) && $settings->type=='get'?'selected':''}} value="get">GET
                            </option>
                            <option {{isset($settings->type) && $settings->type=='post'?'selected':''}} value="post">
                                POST
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Enter Url</label>
                        <input type="text" id="sendUrl" value="{{isset($settings->url)?$settings->url:''}}"
                               placeholder="Enter Url" class="form-control">
                        <small class="text-danger" id="altMessage"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="confirmUrlSend" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="filtered_by_label">
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="{{asset('js/bootstrap-4-navbar.js')}}"></script>

    <script>
        let sidebarAjax;
        $(document).on('click', '.choose_file', function (e) {
            $('.mms_file').trigger('click');
        });

        $(document).on('click', '.choose-from-number', function (e) {
            e.preventDefault();
            const number = $(this).attr('data-number');
            $('.setFromNumber').val(number);
            $('.from-number-text').text(number.substring(0, 5));
        });
        $(document).on('click', '.filter-by', function (e) {
            e.preventDefault();
            const type = $(this).attr('data-type');
            if (type == 'date') {
                $('#reservation').trigger('click');
            }
        });
        $(function () {
            $('#reservation').daterangepicker();
        })
        $(document).on('click', '#sendNumberInfo', function (e) {
            const number = $(this).attr('data-number');
            $('#sendCInfoModal').modal('show');
            $('#confirmUrlSend').attr('data-number', number);
        })
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
        });
        let already_sent = false;
        $(document).on('click', '.new_chat', function (e) {
            e.preventDefault();
            $('.new-chat-section').removeClass('d-none');
            $('.section-new-message').css('display', 'none');
            $('.empty-details-section').addClass('d-none');
            $('.details-section').addClass('d-none');
            $('.chat').removeClass('active');
            $('input[name=chat_type]').val('new_chat');
        });


        function delay(callback, ms) {
            let timer = 0;
            return function () {
                let context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $(document).on('keyup', '#searchContact', delay(function (e) {

            const name = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{route('customer.search.contacts',['page'=>1])}}',
                data: {
                    name: name
                },

                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';

                        $.each(res.data, function (index, value) {
                            html += `<div data-to-number="${value.number}" class="chat row single-contact p-2 mt-1" id="c_${value.id}" data-number="${value.number}"
                                                 data-name="${value.name}" data-id="${value.id}">
                                     <div class="col-md-2 col-2 text-center new-contact-section" id="contact_name_${value.id}">
                                           <strong" class="new-c-name-icon">
                                                        ${value.name.substring(0, 1)}
                                           </strong>
                                     </div>
                                           <div class="col-md-9 text-left">
                                               <h6 class="m-0">${value.name}</h6>
                                                 <h6 class="m-0">${value.number}</h6>
                                           </div>
                                   </div>`;
                        });

                        $('#allContacts').html(html);
                    }
                }

            })
        }, 500))

        $('.select2').select2({
            multiple: true,
            placeholder: 'Select an recipients'
        });

        $(document).on('click', '.chat', function (e) {
            e.preventDefault();
            if($(this).attr('auto-ref') !='true') {
                $('#to-chat').html(`<div class="loading"><div class="loader"></div></div>`);
            }
            $('#mess').html("");
            const number = $(this).attr('data-to-number');
            if (number) {
                $('#label').removeClass("d-none");
            }
            $('.details-section').removeClass('d-none');
            $('.empty-details-section').addClass('d-none');
            $('.new-chat-section').addClass('d-none');

            $('.send').attr('data-to-number', number);
            $('.save').attr('data-to-number', number).addClass('d-none');
            $('#active_number').attr('data-to-number', number);
            $('.chat').removeClass('active');
            $(this).addClass('active');
            $('input[name=chat_type]').val('existing');
            const id= $(this).attr('data-id');

            $('#unread_'+id).remove();

            $.ajax({
                url: '{{route('customer.chat.get.data')}}',
                method: "GET",
                data: {
                    number: number,
                },
                success: function (res) {
                    already_sent = false;
                    $('.section-new-message').css('display', 'flex');
                    if (res.status == 'success') {
                        let html = '';
                        let replyAction='';
                        let last_to_number = null;
                        const messages = res.data.messages.sort((a, b) => new Date(a.updated_at).getTime() - new Date(b.updated_at).getTime());
                        $.each(messages, function (index, value) {
                            let created_at = (new Date(value.created_at)).toLocaleString(undefined, {
                                year: 'numeric',
                                month: 'short',
                                day: '2-digit',
                                hour: 'numeric',
                                minute: 'numeric'
                            });

                            replyAction=`<span class="float-right dropdown d-none">
                                            <span data-toggle="dropdown" class="chat-reply-action"><i class="fas fa-ellipsis-v"></i></span>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a data-message="${value.to}" class="dropdown-item reply-btn block-from" href="#">Reply</a>
                                            </div>
                                        </span>`;

                            if (value.type == 'sent') {
                                html += `<div class="row no-gutters"><div class="col-md-3 offset-md-9" data-toggle="tooltip" data-placement="left" title="From : ${value.from}"><div class="chat-bubble chat-bubble--right ${value.status == 'failed' ? 'failed-message' : ''}">${value.body}</div><small class="message-sent-time ${value.status == 'failed' ? 'text-danger' : ''}">${value.status == 'failed' ? 'Failed' : created_at}</small></div></div>`;
                            } else {
                                html += `<div class="row no-gutters left-chat-action"><div class="left-bubble-title"><span class="left-side-bubble-icon"><i class="fa fa-user mb-1 text-white"></i></span></div><div class="col-md-3" data-toggle="tooltip" data-placement="right" title="To : ${value.to}"><div class="chat-bubble chat-bubble--left">${value.body} ${replyAction}</div><small class="message-sent-time">${created_at}</small></div></div>`;
                                last_to_number = value.to;
                            }

                        });
                        if (res.data.number) {
                            $('#addException').addClass('d-none');
                            $('#removeException').removeClass('d-none');
                        } else {
                            $('#addException').removeClass('d-none');
                            $('#removeException').addClass('d-none');
                        }
                        if (!res.data.label && !res.data.id) {
                            $('.save').attr('have-label', 'no');
                            $('#check_new_contact').removeClass('d-none');
                        } else {
                            $('#check_new_contact').addClass('d-none');
                            $('.save').attr('have-label', 'yes');
                        }

                        $('#to-chat').html(html).scrollTop($('#to-chat')[0].scrollHeight);
                        $('#to-chat').attr('data-current-chats', res.data.page);
                        $('.send').attr('data-id', res.data.id);
                        $('#contactName').html(`<p class="text-dark"> <b>${res.data.name ? res.data.name : ''}</b> </p><p class="text-dark"> ${number ? number : number} </p><small class="text-dark"> ${res.data.address ? res.data.address : ''} </small>`);
                        $('#addException').attr('data-number', number);
                        $('#removeException').attr('data-number', number);
                        $('#select-label').val(res.data.label);
                        $('#sendNumberInfo').attr('data-number', number);
                        $('[data-toggle="tooltip"]').tooltip();
                        if (last_to_number) {
                            $('#number').val(last_to_number).change();
                        }
                    }
                }
            });
            $('.chat').removeAttr('auto-click')
        });

        //Send SMS Ajax Here
        $(document).on('click', '.send', function (e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            let numb = '';
            const chatType = $('input[name=chat_type]').val();
            if (chatType == 'existing') {
                numb = $(this).attr('data-to-number');
            } else {
                numb = $('#new_chat_contact').val();
            }
            if (!numb) {
                $(document).Toasts('create', {
                    autohide: true,
                    delay: 10000,
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Please select a recipient number',
                });
                return true;
            }
            if (chatType == 'existing' && !id) {
                $(document).Toasts('create', {
                    autohide: true,
                    delay: 10000,
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Unknown contact',
                });
                return true;
            }

            const number_to = {id: id, type: 'contact'};
            const from_number = $('#number').val();
            const body = $('#message').val();
            if (!body) {
                $(document).Toasts('create', {
                    autohide: true,
                    delay: 10000,
                    class: 'bg-danger',
                    title: 'Error',
                    body: 'Type your message and try again',
                });
                return true;
            }
            $.ajax({
                url: '{{route('customer.smsbox.compose.sent')}}',
                method: "POST",
                data: {
                    to_numbers: [JSON.stringify(number_to)],
                    from_number: from_number,
                    body: body,
                    from_type: 'phone_number',
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $('.type_message').html('');
                        $('#message').val('');
                        const html = `<div class="row no-gutters"><div class="col-md-3 offset-md-9"><div class="chat-bubble chat-bubble--right">${body}</div></div></div>`;
                        $('#to-chat').append(html).scrollTop($('#to-chat')[0].scrollHeight);
                        setTimeout(()=>{
                            $("#chat-wrapper-single-"+id).trigger('click');
                        },2000);

                    } else {
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-danger',
                            title: 'Notification',
                            body: res.message,
                        });
                    }
                },
                error: function (res) {
                    $(document).Toasts('create', {
                        autohide: true,
                        delay: 10000,
                        class: 'bg-danger',
                        title: 'Notification',
                        body: res.message,
                    });
                }
            });
        });

        $(document).on('change', '#select-label', function (e) {
            $('.save').removeClass('d-none');
        })
        $(document).on('click', '.save', function (e) {

            const label = $('#select-label').val();
            const number = $(this).attr('data-to-number');
            const contact = $(this).attr('have-label');
            if (contact == 'no') {
                $('#addNewContact').modal('show');
                $('#addNewContactBtn').attr('data-label', label).attr('data-number', number);
                return true;
            }
            $.ajax({
                method: "POST",
                url: '{{route('customer.chat.label.update')}}',
                data: {
                    label: label,
                    number: number,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        $('#search').trigger('keyup')
                    }
                }
            });
        });

        $(document).on('click', '#addNewContactBtn', function (e) {

            const label = $(this).attr('data-label');
            const number = $(this).attr('data-number');

            $.ajax({
                method: "POST",
                url: '{{route('customer.add.new.contact')}}',
                data: {
                    label: label,
                    number: number,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $('#addNewContact').modal('hide');
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        const pageNumber = $('#numb').attr('data-current-page');
                        $('#search').attr('search-page-number', parseInt(pageNumber) - 1);
                        $('#search').trigger('keyup');
                    }
                }
            });
        });

        function generateLabel(labels, toNumber, $preLabel) {
            let html = '';
            $.each(labels, function (index, value) {
                html += `<button data-to-number="${toNumber}" have-label="${$preLabel ? 'yes' : 'no'}" data-label="${value.id}" class="dropdown-item label update_label"><span style="color: ${value.color}">${value.title}</span></button>`;
            });
            return html;
        }

        $('#search').on('keyup', function (e) {
            e.preventDefault();
            const search = $(this).val();
            const date = $('#reservation').val();
            const type = $('#sortBy').val();
            const label_id = $('#filtered_by_label').val();
            const prePage = $(this).attr('search-page-number');

            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    search: search,
                    date: date,
                    type: type,
                    label_id: label_id,
                    page: prePage ? prePage : 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.numbers != []) {
                            $.each(res.data.numbers, function (index, value) {
                                let randColor = getRandomColor();
                                html += `<div class="chat p-2 c-pointer" data-to-number="${value.number}">
                                                <div class="row">
                                                    <div class="col-md-2 col-2 text-center name-icon-section">
                                                       <strong style="background: ${randColor}" class="name-icon">  ${value.full_name.substring(0, 1)} </strong>
                                                    </div>
                                                    <div class="col-md-8 col-10 text-left message-body-section">
                                                        <h6>${value.full_name ? value.full_name : value.number}</h6>
                                                        <h6>${value.body}</h6>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2 d-lg-block d-none text-sm">${value.created_at}</div>
                                                </div>
                                         </div>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);

                        if (res.data.page != 'end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        } else {
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        });

        $(document).ready(function (e) {
            chatList();
        })


        // Chat List Function
        function chatList(){
            const prePage = $(this).attr('search-page-number');
            sidebarAjax=$.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    page: prePage ? prePage : 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        let dataNumber=$('.send').attr('data-to-number');
                        if (res.data.numbers != []) {
                            $.each(res.data.numbers, function (index, value) {
                                let unread='';

                                let  uniqueCounter=value.number.replace('+','');
                                if(value.unread > 0){
                                    unread=`<span id="unread_${value.id}" class="unread-msg-counter">${value.unread}</span>`;
                                }
                                let randColor = getRandomColor();
                                html += `<div class="chat p-2 c-pointer ${dataNumber && dataNumber==value.number?'active':''}" trigger="${value.number.replace('+','')}" auto-ref="${dataNumber && dataNumber==value.number?'true':'false'}" id="chat-wrapper-single-${value.id}" data-id="${value.id}" data-to-number="${value.number}">
                                                <div class="row created-at-for-new-msg" data-date="${value.createdAt}">
                                                    <div class="col-md-2 col-2 text-center name-icon-section">
                                                       <span class="left-side-bubble-icon"><i class="fa fa-user mb-1 text-white"></i></span>
                                                    </div>
                                                    <div class="col-md-8 col-10 text-left message-body-section">
                                                        <h6 class="m-0 con-name">${value.full_name ? value.full_name : value.number}  ${unread}</h6>
                                                        <h6 class="m-0">${value.body}</h6>
                                                    </div>
                                                     <div class="col-md-2 col-lg-2 d-lg-block d-none text-sm">

                                                            <small>
                                                            ${value.created_at}
                                                            </small>
                                                            <div class="text-center dropdown">
                                                            <span data-toggle="dropdown" class="chat-from-action"><i class="fas fa-ellipsis-v"></i></span>
                                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a data-message="Are you sure you want to block this number?" data-action="{{route('customer.exception')}}" data-input="{&quot;type&quot;:&quot;block&quot;,&quot;number&quot;:&quot;${value.number}&quot;}" data-toggle="modal" data-target="#modal-confirm" class="dropdown-item block-from" href="#">Block</a>
                                                                <a data-message="Are you sure you want to delete full conversation?" data-action="{{route('customer.chat.delete')}}" data-input="{&quot;number&quot;:&quot;${value.number}&quot;}" data-toggle="modal" data-target="#modal-confirm" class="dropdown-item block-from" href="#">Delete</a>

                                                              </div>
                                                            </div>
                                                     </div>
                                                </div>
                                         </div>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);

                        if (res.data.page != 'end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        } else {
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        }


        // Send Request Con
        setInterval(function () {
            const page = $('#numb').attr('data-current-page');
            $('.newMessage').remove();

            let nmbr = $('.send').attr('data-to-number');

            if (page && page == 2) {
                chatList();
                if (nmbr) {
                    $('.send').attr('scroll-top-height', this.scrollTop).attr('scroll-height', this.scrollHeight).attr('offset-height', this.offsetHeight);
                    const scrollTop= parseInt($('.send').attr('scroll-top-height'));
                    const scrollHeight= parseInt($('.send').attr('scroll-height'));
                    const offsetHeight= parseInt($('.send').attr('offset-height'));


                    if((scrollHeight - scrollTop-offsetHeight)<=500){
                        nmbr = nmbr.replace('+', '');
                        $(`.chat[trigger=${nmbr}]`).trigger('click');
                    }
                }

            } else {
                let time = [];
                $('.created-at-for-new-msg').each(function () {
                    time.push($(this).attr('data-date'));
                });
                // newMessage(time[0]);

                if (nmbr) {
                    nmbr = nmbr.replace('+', '');
                    $(`.chat[trigger=${nmbr}]`).trigger('click');
                }
            }
        }, 10000);


        function newMessage(time){
            $.ajax({
                url: '{{route('customer.chat.get.new')}}',
                method: "get",
                data:{
                    time:time
                },
                success: function (res) {
                    if (res.status == 'success') {
                        if(res.data && res.data > 0) {
                            let newHtml = `<div class="newMessage">{{trans('customer.message.new_message')}} <span class="float-right">${res.data}</span></div>`;
                            $('.newMessageAlert').html(newHtml);
                        }else{
                            $('.newMessageAlert').html('');
                        }
                    }
                }
            });
        }


        $(document).on('click', '.newMessageAlert', function(e){
            $('.newMessage').remove();
            chatList();
        })


        $('#sortBy').on('change', function (e) {
            e.preventDefault();
            const type = $(this).val();
            const search = $('#search').val();
            const date = $('#reservation').val();
            const label_id = $('#filtered_by_label').val();
            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    type: type,
                    search: search,
                    date: date,
                    label_id: label_id,
                    page: 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.page != 'end') {

                            $.each(res.data.numbers, function (index, value) {
                                html += `<div class="chat p-2 c-pointer" data-to-number="${value.number}">
                                                <div class="row">
                                                    <div class="col-md-2 col-2 text-center name-icon-section">
                                                       <strong class="name-icon">  ${value.full_name.substring(0, 1)} </strong>
                                                    </div>
                                                    <div class="col-md-8 col-10 text-left message-body-section">
                                                        <h6>${value.full_name ? value.full_name : value.number}</h6>
                                                        <h6>${value.body}</h6>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2 d-lg-block d-none text-sm">${value.created_at}</div>
                                                </div>
                                         </div>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);
                        if (res.data.page != 'end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        } else {
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        });

        $(document).on('click', '.applyBtn', function (e) {
            e.preventDefault();
            const date = $('#reservation').val();
            const type = $('#sortBy').val();
            const search = $('#search').val();
            const label_id = $('#filtered_by_label').val();
            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    date: date,
                    type: type,
                    search: search,
                    label_id: label_id,
                    page: 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.page != 'end') {

                            $.each(res.data.numbers, function (index, value) {
                                html += `<div class="chat p-2 c-pointer" data-to-number="${value.number}">
                                                <div class="row">
                                                    <div class="col-md-2 col-2 text-center name-icon-section">
                                                       <strong class="name-icon">  ${value.full_name.substring(0, 1)} </strong>
                                                    </div>
                                                    <div class="col-md-8 col-10 text-left message-body-section">
                                                        <h6>${value.full_name ? value.full_name : value.number}</h6>
                                                        <h6>${value.body}</h6>
                                                    </div>
                                                   <div class="col-md-2 col-lg-2 d-lg-block d-none text-sm">${value.created_at}</div>
                                                </div>
                                         </div>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        $('#numb').html(html);
                        if (res.data.page != 'end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        } else {
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                }
            });
        });

        $(document).on('click', '.filtered_by_label', function (e) {
            e.preventDefault();
            const label_id = $(this).attr('data-id');
            $('#filtered_by_label').val(label_id);
            const date = $('#reservation').val();
            const type = $('#sortBy').val();
            const search = $('#search').val();

            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "get",
                data: {
                    label_id: label_id,
                    search: search,
                    date: date,
                    type: type,
                    page: 1,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';
                        let labels = '';
                        if (res.data.page != 'end') {

                            $.each(res.data.numbers, function (index, value) {
                                html += `<div class="chat p-2 c-pointer" data-to-number="${value.number}">
                                                <div class="row">
                                                    <div class="col-md-2 col-2 text-center name-icon-section">
                                                       <strong class="name-icon">  ${value.full_name.substring(0, 1)} </strong>
                                                    </div>
                                                    <div class="col-md-8 col-10 text-left message-body-section">
                                                        <h6>${value.full_name ? value.full_name : value.number}</h6>
                                                        <h6>${value.body}</h6>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2 d-lg-block d-none text-sm">${value.created_at}</div>
                                                </div>
                                         </div>`;
                            });
                        } else {
                            html = '<div class="row"><div class="col-sm-12 text-center">No Data Available</div><div>';
                        }
                        if (res.data.page != 'end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        } else {
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                        $('#numb').html(html);
                    }
                }
            });
        });


        function difference(first, sec) {
            return Math.abs(first - sec);
        }

        let currentPageNumber = 1;
        $('#next-page').on('click', function (e) {
            e.preventDefault();
            if(sidebarAjax){
                sidebarAjax.abort()
            }

            $('.ajax-loader').removeClass('d-none');
            const chats = $('#numb').attr('data-current-page');
            const page = chats;
            const search = $('#search').val();
            const label_id = $('#filtered_by_label').val();
            const date = $('#reservation').val();
            const type = $('#sortBy').val();

            if (chats != 'end') {
                getPaginationData(page, search, label_id, date, type)
                currentPageNumber++;
                $('#next-page').removeClass('disabled').removeAttr('disabled', 'disabled');
                $('#previous-page').removeClass('disabled').removeAttr('disabled', 'disabled');
            } else {
                $('#next-page').addClass('disabled').attr('disabled', 'disabled');
            }
        });

        $('#previous-page').on('click', function (e) {
            e.preventDefault();
            $('.ajax-loader').removeClass('d-none');
            const chats = $('#numb').attr('data-current-page');
            if (chats > 1 || chats == 'end') {

                const page = chats == 'end' ? currentPageNumber - 1 : parseInt(chats) - 2;
                const search = $('#search').val();
                const label_id = $('#filtered_by_label').val();
                const date = $('#reservation').val();
                const type = $('#sortBy').val();
                getPaginationData(page, search, label_id, date, type)
                currentPageNumber--;
                $('#previous-page').removeClass('disabled').removeAttr('disabled', 'disabled');
                $('#next-page').removeClass('disabled').removeAttr('disabled', 'disabled');

            } else {
                $('#previous-page').addClass('disabled').attr('disabled', 'disabled');
                $('#next-page').addClass('disabled').attr('disabled', 'disabled');
            }
        });

        function getPaginationData(page, search, label_id, date, type) {
            $.ajax({
                url: '{{route('customer.chat.get.numbers')}}',
                method: "GET",
                data: {
                    page: page,
                    search: search,
                    label_id: label_id,
                    date: date,
                    type: type,
                },
                success: function (res) {
                    already_sent = false;
                    if (res.status == 'success') {
                        if (res.data.numbers) {
                            let html = '';
                            let labels = '';


                            $.each(res.data.numbers, function (index, value) {
                                let unread='';

                                if(value.unread > 0){
                                    unread=`<span id="unread_${value.id}" class="unread-msg-counter">${value.unread}</span>`;
                                }

                                html += `<div class="chat p-2 c-pointer" data-to-number="${value.number}">
                                                <div class="row created-at-for-new-msg" data-date="${value.createdAt}">
                                                    <div class="col-md-2 col-2 text-center name-icon-section">
                                                       <span class="left-side-bubble-icon"><i class="fa fa-user mb-1 text-white"></i></span>
                                                    </div>
                                                    <div class="col-md-8 col-10 text-left message-body-section">
                                                        <h6>${value.full_name ? value.full_name : value.number} ${unread}</h6>
                                                        <h6>${value.body}</h6>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2 d-lg-block d-none text-sm">

                                                            <small>
                                                            ${value.created_at}
                                                            </small>
                                                            <div class="text-center dropdown">
                                                            <span data-toggle="dropdown" class="chat-from-action"><i class="fas fa-ellipsis-v"></i></span>
                                                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a data-message="Are you sure you want to block this number?" data-action="{{route('customer.exception')}}" data-input="{&quot;type&quot;:&quot;block&quot;,&quot;number&quot;:&quot;${value.number}&quot;}" data-toggle="modal" data-target="#modal-confirm" class="dropdown-item block-from" href="#">Block</a>
                                                                <a data-message="Are you sure you want to delete full conversation?" data-action="{{route('customer.chat.delete')}}" data-input="{&quot;number&quot;:&quot;${value.number}&quot;}" data-toggle="modal" data-target="#modal-confirm" class="dropdown-item block-from" href="#">Delete</a>

                                                              </div>
                                                            </div>
                                                     </div>
                                                </div>
                                         </div>`;
                            });
                            $('#numb').html(html);
                        }
                        if (res.data.page != 'end') {
                            $('#cPage').text(parseInt(res.data.page) - 1);
                        } else {
                            $('#cPage').text(res.data.page);
                        }
                        $('#numb').attr('data-current-page', res.data.page);
                    }
                    $('.ajax-loader').addClass('d-none');
                },
                error: function (res) {
                    $('.ajax-loader').addClass('d-none');
                }
            });
        }


        $(document).on('click', '.update_label', function (e) {
            e.preventDefault();
            const label = $(this).attr('data-label');
            const number = $(this).attr('data-to-number');
            const contact = $(this).attr('have-label');
            if (contact == 'no') {
                $('#addNewContact').modal('show');
                $('#addNewContactBtn').attr('data-label', label).attr('data-number', number);
                return true;
            }
            $.ajax({
                method: "POST",
                url: '{{route('customer.chat.label.update')}}',
                data: {
                    label: label,
                    number: number,
                    _token: '{{csrf_token()}}'
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        const pageNumber = $('#numb').attr('data-current-page');
                        $('#search').attr('search-page-number', parseInt(pageNumber) - 1);
                        $('#search').trigger('keyup');
                    }
                }
            });
        });


        $('#to-chat').on('scroll', function () {
            $('.send').attr('scroll-top-height', this.scrollTop).attr('scroll-height', this.scrollHeight).attr('offset-height', this.offsetHeight);
            if (this.scrollTop < 20) {
                if (!already_sent) {
                    already_sent = true;
                    const chats = $('#to-chat').attr('data-current-chats');
                    const number = $('#active_number').attr('data-to-number');

                    if (chats != 'end') {
                        $.ajax({
                            url: '{{route('customer.chat.get.chats')}}',
                            method: "GET",
                            data: {
                                chats: chats,
                                number: number,
                            },
                            success: function (res) {
                                already_sent = false;
                                if (res.status == 'success') {
                                    let html = '';
                                    $.each(res.data.messages, function (index, value) {
                                        let created_at = (new Date(value.created_at)).toLocaleString();
                                        if (value.type == 'sent') {
                                            html += `<div class="row no-gutters"><div class="col-md-3 offset-md-9" data-toggle="tooltip" data-placement="left" title="From : ${value.from}"><div class="chat-bubble chat-bubble--right">${value.body}</div><small class="message-sent-time">${created_at}</small></div></div>`;
                                        } else {
                                            html += `<div class="row no-gutters"><div class="col-md-3" data-toggle="tooltip" data-placement="right" title="To : ${value.to}"><div class="chat-bubble chat-bubble--left">${value.body}</div></div></div>`;
                                        }
                                    });
                                    $('#to-chat').prepend(html);
                                    $('#to-chat').attr('data-current-chats', res.data.page);
                                    $('[data-toggle="tooltip"]').tooltip();
                                }
                            }
                        });
                    }
                }
            }else{
                // $('#to-chat').attr('data-current-chats', 2);
            }
        });

        $(document).on('click', '.response_value', function (e) {
            let value = $(this).attr('data-title');
            var curPos = document.getElementById("message").selectionStart;
            let x = $("#message").val();
            $("#message").val(x.slice(0, curPos) + value + x.slice(curPos)).focus();
            $('.emoji-wysiwyg-editor').append(value).focus();
            checkCharecter();
            $('#showResponse').addClass('d-none');
        });

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        function checkCharecter() {
            var messageValue = $('#message').val();
            var div = parseInt(parseInt(messageValue.length - 1) / 160) + 1;
            if (div <= 1) {
                $("#count").text("Characters left: " + (160 - messageValue.length));
            } else {
                $("#count").text("Characters left: " + (160 * div - messageValue.length) + "/" + div);
            }
        }

        $(document).on('click', '#message', function (e) {
            checkCharecter();
            $('#showResponse').removeClass('d-none');
        });
        $(document).on('click', '.type_message', function (e) {
            checkCharecter();
            $('#showResponse').removeClass('d-none');
        });

        $(document).on('keyup', '#message', function (e) {
            checkCharecter();
            $('#showResponse').addClass('d-none');
        });

        $(document).mouseup(function (e) {
            var container = $("#message");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $("#showResponse").addClass('d-none');
            }
        });

        $(document).on('click', '.exception_modal_btn', function (e) {
            $('#exceptionModal').modal('show');
            const number = $(this).attr('data-number');
            const type = $(this).attr('data-type');
            $('#saveException').attr('data-number', number).attr('data-type', type);
            if (type == 'add') {
                $('.exception_message').text('Are you sure to add this number in Exception list')
            } else {
                $('.exception_message').text('Are you sure to remove this number from Exception list')
            }

        });

        $(document).on('click', '#saveException', function (e) {

            const number = $(this).attr('data-number');
            const type = $(this).attr('data-type');
            const check_add_contact = $('#checkInput:checked').val();

            $.ajax({
                type: 'post',
                url: '{{route('customer.exception')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    number: number,
                    type: type,
                    check_add_contact: check_add_contact,
                },
                success: function (res) {
                    if (res.status == 'success') {
                        if (res.type == 'add') {
                            $('#removeException').attr('data-number', number).removeClass('d-none');
                            $('#addException').addClass('d-none');
                            $('#exceptionModal').modal('hide');
                            $('#check_new_contact').addClass('d-none');
                            $(document).Toasts('create', {
                                autohide: true,
                                delay: 3000,
                                class: 'bg-success',
                                title: 'Notification',
                                body: 'Suppress added successfully',
                            });
                        } else {
                            $('#removeException').addClass('d-none');
                            $('#addException').removeClass('d-none').attr('data-number', number);
                            $('#exceptionModal').modal('hide');
                            $('#check_new_contact').addClass('d-none');
                            $(document).Toasts('create', {
                                autohide: true,
                                delay: 3000,
                                class: 'bg-success',
                                title: 'Notification',
                                body: 'Suppress removed successfully',
                            });
                        }
                    }

                }
            })
        });
        $('#sendUrl').on('click', function (e) {
            $('#altMessage').text(' ')
            $('#sendUrl').css('border', '1px solid white');
        })
        $(document).on('click', '#confirmUrlSend', function (e) {
            e.preventDefault();
            $('#confirmUrlSend').html(' <i class="fa fa-spinner fa-spin"></i> Loading')
            const number = $(this).attr('data-number');
            const data_url = $('#sendUrl').val();
            const method = $('#sendUrlMethod').val();
            if (!data_url) {
                $('#sendUrl').css('border', '1px solid red');
                $('#altMessage').text('Enter url first')
                $(document).Toasts('create', {
                    autohide: true,
                    delay: 3000,
                    class: 'bg-danger',
                    title: 'Notification',
                    body: 'Please enter url first',
                });
                return true;
            } else {
                $('#sendUrl').css('border', '1px solid white');
            }
            $.ajax({
                type: 'POST',
                url: '{{route('customer.send.contact.data')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    number: number, url: data_url, url_method: method
                },
                success: function (res) {
                    $(document).Toasts('create', {
                        autohide: true,
                        delay: 3000,
                        class: 'bg-success',
                        title: 'Notification',
                        body: 'Data sent successfully',
                    });

                    $('#confirmUrlSend').text('Confirm')
                    $('#sendCInfoModal').modal('hide');
                }
            });
        });

        $('#sortBy').trigger('change');

        $(document).on('click','.block-from',function(e){
            e.preventDefault();
            return false;
        })
        $(document).on('click','.delete-from',function(e){
            e.preventDefault();
            return false;
        })

    </script>


    <!-- For Emoji -->
    <!-- Begin emoji-picker JavaScript -->
    <script src="{{asset('emoji/js/config.min.js')}}"></script>
    <script src="{{asset('emoji/js/util.min.js')}}"></script>
    <script src="{{asset('emoji/js/jquery.emojiarea.min.js')}}"></script>
    <script src="{{asset('emoji/js/emoji-picker.min.js')}}"></script>
    <!-- End emoji-picker JavaScript -->

    <script>
        $(function () {
            // Initializes and creates emoji set from sprite sheet
            window.emojiPicker = new EmojiPicker({
                emojiable_selector: '[data-emojiable=true]',
                assetsPath: '{{asset('/emoji/img')}}',
                popupButtonClasses: 'fa fa-smile-o'
            });
            // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
            // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
            // It can be called as many times as necessary; previously converted input fields will not be converted again
            window.emojiPicker.discover();
        });
    </script>

@endsection
