<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    @if(auth('customer')->user()->type=='staff')
        <li class="nav-item">
            <span class="inbox_counter  counter d-none">0</span>
            <a href="{{route('customer.chat.index')}}" class="nav-link {{isSidebarActive('customer.chat.index')}}">
                <i class="nav-icon fas fa-sms n-danger-c"></i>
                <p>
                    {{trans('customer.chat')}}
                </p>
            </a>
        </li>

    @else
    {{-- <li class="nav-item dropdown user-menu mr-3 top-up-section ml-3">
        <div class="row credit-section card">
            <div class="card-body pb-0 pl-0">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="">
                            <div class="sms-wallet-text">Balance</div>
                        </div>

                    </div>
                </div>

                <div class="col-sm-12 sender-border m-2">
                    <div class="sender-text"><strong>{{isset(wallet()->credit)?wallet()->credit:0}}</strong> <span class="text_available">(Available)</span></div>
                </div>
            </div>

        </div>
    </li> --}}

    {{-- <li class="nav-item sidebar-compose d-none">
        <a href="{{route('customer.smsbox.compose')}}" class="nav-link {{isSidebarActive('customer.compose')}}">
            <i class="nav-icon fas fa-plus-circle n-primary-c"></i>
            <p>
                {{trans('customer.compose')}}
            </p>
        </a>
    </li> --}}

    <!--Dashboard  -->
    <li class="nav-item d-none" >
        <a href="{{route('customer.dashboard')}}" class="nav-link {{isSidebarActive('customer.dashboard')}}">
            <i class="nav-icon fas fa-tachometer-alt n-info-c"></i>
            <p>
                {{trans('customer.dashboard')}}
            </p>
        </a>
    </li>
    {{-- celeste resources --}}
    <li class="nav-item celeste_resource">
        {{-- <a href="{{route('customer.dashboard')}}" class="nav-link {{isSidebarActive('customer.dashboard')}}"> --}}
            {{-- <i class="nav-icon fas fa-tachometer-alt n-info-c"></i> --}}
            <p class="mb-0">
                {{trans('admin.celeste_resources')}}
            </p>
            <div class="login_info">{{trans('admin.lest_login')}}</div>
            <div class="login_info"> {{auth('customer')->user()->updated_at->format('Y-m-d') }}</div>
            <div class="login_info">{{trans('Malaysia Time')}}</div>
        {{-- </a> --}}
    </li>
    <!-- Overview  -->
    <li class="nav-item overview mt-4">
            <p class="mb-1">
                {{trans('admin.overview')}}
            </p>
            <div class="overview-info">
                <p class="mb-0">
                    {{trans('admin.business_corporate')}}
                </p>
                <p class="mb-0">
                    {{trans('admin.banking')}}
                </p>
            </div>
    </li>
        {{-- ibft --}}
     {{-- <li class="nav-item mt-3">
        <a href="{{route('admin.customer.ibft.transfer')}}" class="nav-link {{isSidebarActive('admin.customer.ibft.transfer')}}">
            <p class="mb-0">
                {{trans('admin.ibft_t')}}
            </p>
        </a>
    </li> --}}
    <li class="nav-item mt-4 has-treeview {{isSidebarTrue(['admin.customer.ibft.transfer.list','admin.customer.ibft.transfer'])? 'menu-open' : ''}}">
        <a href="#"
           class="nav-link {{isSidebarTrue(['admin.customer.ibft.transfer.list','admin.customer.ibft.transfer'])? 'active nav-link-active' : ''}}">
            <p>
                {{trans('admin.ibft_t')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['admin.customer.ibft.transfer.list','admin.customer.ibft.transfer'])? 'block': 'none'}};">
            <li class="nav-item">
                <a href="{{route('admin.customer.ibft.transfer.list')}}" class="nav-link {{isSidebarActive('admin.customer.ibft.transfer.list')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('admin.ibft_list')}}
                    </p>
                </a>
            </li>
        </ul>
    </li>
        {{-- important information --}}
     <li class="nav-item">
        <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <p class="mb-0">
                {{trans('admin.important_info')}}
            </p>
        </a>
    </li>
        {{-- online secunity alert --}}
    <li class="nav-item">
        <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <p class="mb-0">
                {{trans('admin.online_secunity_alert')}}
            </p>
        </a>
    </li>
        {{-- online secunity alert --}}
    <li class="nav-item">
        <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <p class="mb-0">
                {{trans('admin.myr_term_deposit')}}
            </p>
        </a>
    </li>
        {{-- scheduled sevice --}}
    <li class="nav-item">
         <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
             <p class="mb-0">
                {{trans('admin.scheduled_sevice')}}
            </p>
        </a>
    </li>
        {{-- glc approval --}}
    <li class="nav-item">
        <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <p class="mb-0">
                {{trans('admin.glc_approval')}}
            </p>
        </a>
    </li>
        {{-- on hold status --}}
    <li class="nav-item">
        <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <p class="mb-0">
                {{trans('admin.on_hold_status')}}
            </p>
        </a>
    </li>
        {{-- issue code --}}
    <li class="nav-item">
        <a href="#" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <p class="mb-0">
                {{trans('admin.issue_code')}}
            </p>
        </a>
    </li>
    <!--Campaign  -->
    {{-- <li class="nav-item has-treeview {{isSidebarTrue(['customer.campaign.index','customer.sms.template.*'])? 'menu-open' : ''}}">
        <a href="#"
           class="nav-link {{isSidebarTrue(['customer.campaign.index','customer.sms.template.*'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fa fa-bullhorn n-warning-c"></i>
            <p>
                {{trans('customer.campaign')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['customer.campaign.*','customer.sms.template.*'])? 'block': 'none'}};">
            <li class="nav-item">
                <a href="{{route('customer.campaign.create')}}" class="nav-link {{isSidebarActive('customer.campaign.create')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.campaign_create')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.campaign.index')}}" class="nav-link {{isSidebarActive('customer.campaign.index')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.campaign_list')}}
                    </p>
                </a>
            </li>


            <li class="nav-item">
                <a href="{{route('customer.sms.template.index')}}" class="nav-link {{isSidebarActive('customer.sms.template.*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('Create SMS Template')}}
                    </p>
                </a>
            </li>
        </ul>
    </li> --}}

    <!--Dynamic Campaign  -->
    {{-- <li class="nav-item has-treeview {{isSidebarTrue(['customer.dynamic.campaign','customer.dynamic.campaign.*','customer.dynamic-template.*'])? 'menu-open' : ''}}">
        <a href="#"
           class="nav-link {{isSidebarTrue(['customer.dynamic.campaign','customer.dynamic.campaign.*','customer.dynamic-template.*'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fa fa-bullhorn n-success-c"></i>
            <p>
                {{trans('Personalize Campaign')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['customer.dynamic.campaign','customer.dynamic-template.*','customer.dynamic.campaign.*'])? 'block': 'none'}};">
            <li class="nav-item">
                <a href="{{route('customer.dynamic.campaign.create')}}" class="nav-link {{isSidebarActive('customer.dynamic.campaign.create')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.campaign_create')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.dynamic.campaign')}}" class="nav-link {{isSidebarActive('customer.dynamic.campaign')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.campaign_list')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('customer.dynamic-template.index')}}" class="nav-link {{isSidebarActive('customer.dynamic-template.*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('Template')}}
                    </p>
                </a>
            </li>


        </ul>
    </li> --}}


    {{-- <li  class="nav-item has-treeview d-none  {{isSidebarTrue(['customer.smsbox.*','customer.smsbox.overview'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{isSidebarTrue(['customer.smsbox.*','customer.smsbox.overview'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fas fa-envelope n-primary-c"></i>
            <p>
                {{trans('customer.message')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{isSidebarTrue(['customer.smsbox.*','customer.smsbox.overview'])? 'block': 'none'}}">

            <li class="nav-item d-none">
                <a href="{{route('customer.smsbox.compose',['type'=>'voice_call'])}}"
                   class="nav-link {{request()->get('type') == 'voice_call'?'active':''}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.sent_using_voice_call')}}
                    </p>
                </a>
            </li>
        </ul>
    </li> --}}

    <!--Senders  -->
    {{-- <li class="nav-item has-treeview {{isSidebarTrue(['customer.numbers*','customer.from-group.*','customer.sender-id.*','customer.whatsapp.numbers','customer.groups.*'])? 'menu-open' : ''}}">
        <a href="{{route('customer.campaign.index')}}" class="nav-link {{isSidebarTrue(['customer.numbers.*','customer.from-group.*','customer.sender-id.*','customer.whatsapp.numbers','customer.groups.*'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fab fa-telegram n-info-c"></i>
            <p>
                {{trans('customer.senders')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{isSidebarTrue(['customer.numbers.*','customer.from-group.*','customer.sender-id.*','customer.whatsapp.numbers','customer.groups.*'])? 'block': 'none'}}">
            <li class="nav-item" id="phone-number">
                <a href="{{route('customer.numbers.phone_numbers')}}"
                   class="nav-link {{isSidebarActive('customer.numbers.phone_numbers')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.phone_number')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.sender-id.index')}}"
                   class="nav-link {{isSidebarActive('customer.sender-id*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.sender_id')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.from-group.index')}}"
                   class="nav-link {{isSidebarActive('customer.from-group.*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.from_group')}}
                    </p>
                </a>
            </li>

        </ul>
    </li> --}}

    <!--Chat Box  -->
    {{-- <li class="nav-item has-treeview {{isSidebarTrue(['customer.chat.index','customer.chat.response'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{isSidebarTrue(['customer.chat.index','customer.chat.response'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fas fa-sms n-danger-c"></i>
            <p>
                {{trans('Chat Box')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>

        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['customer.chat.index','customer.chat.response'])? 'block': 'none'}};">

            <li class="nav-item">
                <span class="inbox_counter  counter d-none">0</span>
                <a href="{{route('customer.chat.index')}}" class="nav-link {{isSidebarActive('customer.chat.index')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.chat')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('customer.chat.response')}}"
                   class="nav-link {{isSidebarActive('customer.chat.response')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('Chat Response')}}
                    </p>
                </a>
            </li>
        </ul>
    </li> --}}

    <!--Contacts  -->
    {{-- <li class="nav-item has-treeview {{isSidebarTrue(['customer.opt.out.number','customer.group.records','customer.label.*','customer.contacts.*'])? 'menu-open' : ''}}">
        <a href="{{route('customer.contacts.index')}}" class="nav-link {{isSidebarTrue(['customer.opt.out.number','customer.group.records','customer.label.*','customer.contacts.*'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fas fa-phone-alt n-warning-c"></i>
            <p>
                {{trans('customer.contacts')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{isSidebarTrue(['customer.opt.out.number','customer.group.records','customer.label.*','customer.contacts.*'])? 'block': 'none'}}">
            <li class="nav-item">
                <a href="{{route('customer.contacts.index')}}"
                   class="nav-link {{isSidebarActive('customer.contacts.*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.list')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('customer.group.records')}}"
                   class="nav-link {{isSidebarActive('customer.group.records')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.builder')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('customer.label.index')}}" class="nav-link {{isSidebarActive('customer.label.*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.labels')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.groups.index')}}"
                   class="nav-link {{isSidebarActive('customer.groups.*')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('customer.groups')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.opt.out.number')}}"
                   class="nav-link {{isSidebarActive('customer.opt.out.number')}}">
                    <i class="nav-icon fa fa-angle-double-right"></i>
                    <p>
                        {{trans('Opt-out List')}}
                    </p>
                </a>
            </li>

        </ul>

    </li> --}}

    <!--Keyword  -->
    {{-- <li class="nav-item">
        <a href="{{route('customer.keywords.index')}}" class="nav-link {{isSidebarActive('customer.keywords.*')}}">
            <i class="nav-icon fas fa-file-word n-success-c"></i>
            <p>
                {{trans('customer.keywords')}}
            </p>
        </a>
    </li> --}}

    <!--Report  -->
    {{-- <li class="nav-item has-treeview {{isSidebarTrue(['customer.transactions','customer.message.reports'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{isSidebarTrue(['customer.transactions','customer.message.reports'])? 'active nav-link-active' : ''}}">
            <i class="nav-icon fa fa-info n-info-c"></i>
            <p>
                Reports
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['customer.transactions','customer.message.reports'])? 'block': 'none'}};">

            <li class="nav-item">
                <a href="{{route('customer.transactions')}}" class="nav-link {{isSidebarActive('customer.transactions')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('Transactions')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.message.reports')}}" class="nav-link {{isSidebarActive('customer.message.reports')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('Messages')}}
                    </p>
                </a>
            </li>


        </ul>
    </li> --}}

    <!--Billing  -->
    {{-- <li class="nav-item">
        <a href="{{route('customer.billing.index')}}" class="nav-link {{isSidebarActive('customer.billing.*')}}">
            <i class="nav-icon fas fa-file-invoice-dollar n-primary-c"></i>
            <p>
                {{trans('customer.billing')}}
            </p>
        </a>
    </li>


    <li class="nav-item">
        <a href="{{route('customer.staff.index')}}" class="nav-link {{isSidebarActive('customer.staff.*')}}">
            <i class="nav-icon fa fa-user-plus n-warning-c" aria-hidden="true"></i>
            <p>
                {{trans('Staff')}}
            </p>
        </a>
    </li> --}}


    <!--Ticket  -->
    {{-- <li class="nav-item">
        <a href="{{route('customer.ticket.index')}}" class="nav-link {{isSidebarActive('customer.ticket.index')}}">
            <i class="nav-icon fas fa-question-circle n-success-c"></i>
            <p>
                {{trans('admin.ticket.ticket')}}
            </p>
        </a>
    </li> --}}

    <!--Frontend  -->
    @if(auth('customer')->user()->type=='reseller')
        @if(checkModule('landing_page'))
            <li class="nav-item">
                <a href="{{route('customer.template')}}" class="nav-link {{isSidebarActive('customer.template')}}">
                    <i class="nav-icon fas fa-laptop n-danger-c"></i>
                    <p>
                        {{trans('Frontend')}}
                    </p>
                </a>
            </li>
        @endif
    @endif

    <!--Settings  -->
    <li class="nav-item d-none">
        <a href="{{route('customer.settings.index')}}" class="nav-link {{request()->get('type') == 'settings'?'':isSidebarActive('customer.settings.*') }}">
            <i class="nav-icon fas fa-cog n-warning-c"></i>
            <p>
                {{trans('customer.settings')}}
            </p>
        </a>
    </li>

    <!--Developer  -->
    @if(api_availability())
        <li class="nav-item">
            <a href="{{route('customer.authorization.token.create')}}"
               class="nav-link {{isSidebarActive('customer.authorization.token.create')}}">
                <i class="fas fa-laptop-code nav-icon n-info-c"></i>
                <p>
                    {{trans('Developer')}}
                </p>
            </a>
        </li>
    @endif


    @if(auth('customer')->user()->type=='reseller')

    <li class="nav-header mt-2 ml-2 mr-2 custom-separator"></li>

    <li class="nav-item reseller-sections pl-1 text-muted pt-2">
        <h6>{{trans('Reseller Panel')}}</h6>
    </li>


        <!--Reseller Customers  -->
        <li class="nav-item">
            <a href="{{route('customer.reseller-customers.index')}}" class="nav-link {{isSidebarActive('customer.reseller-customers.*')}}">
                <i class="nav-icon fa fa-angle-double-right n-primary-c"></i>
                <p>
                    {{trans('customer.customer')}}
                </p>
            </a>
        </li>

        <!--Reseller TopUp Request  -->
        <li class="nav-item">
            <span class="topup_counter  counter d-none">0</span>
            <a href="{{route('customer.topup.request')}}" class="nav-link {{isSidebarActive('customer.topup.request')}}">
                <i class="fa fa-angle-double-right nav-icon n-success-c"></i>
                <p>
                    {{trans('admin.topup_request')}}
                </p>
            </a>
        </li>

        <!--Reseller Plan  -->
        <li class="nav-item">
            <span class="plan_counter  counter d-none">0</span>
            <a href="{{route('customer.plans.index')}}" class="nav-link {{isSidebarActive('customer.plans.*')}}">
                <i class="nav-icon fa fa-angle-double-right n-info-c"></i>
                <p>
                    {{trans('customer.plan')}}
                </p>
            </a>
        </li>

        <!--Reseller Coverage  -->
        <li class="nav-item">
            <span class="plan_counter  counter d-none">0</span>
            <a href="{{route('customer.coverage.index')}}" class="nav-link {{isSidebarActive('customer.coverage.*')}}">
                <i class="nav-icon fa fa-angle-double-right n-warning-c"></i>
                <p>
                    {{trans('Coverage')}}
                </p>
            </a>
        </li>

        <!--Reseller Customer Reports  -->
        <li class="nav-item has-treeview {{isSidebarTrue(['customer.user.message.reports','customer.user.transactions'])? 'menu-open' : ''}}">
            <a href="#" class="nav-link {{isSidebarTrue(['customer.user.message.reports','customer.user.transactions'])? 'active nav-link-active' : ''}}">
                <i class="nav-icon fa fa-file-invoice n-primary-c"></i>
                <p>
                    Reports
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview"
                style="display: {{isSidebarTrue(['customer.user.message.reports','customer.user.transactions'])? 'block': 'none'}};">


                <li class="nav-item">
                    <a href="{{route('customer.user.transactions')}}" class="nav-link {{isSidebarActive('customer.user.transactions')}}">
                        <i class="fa fa-angle-double-right nav-icon"></i>
                        <p>
                            {{trans('Transactions')}}
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('customer.user.message.reports')}}" class="nav-link {{isSidebarActive('customer.user.message.reports')}}">
                        <i class="fa fa-angle-double-right nav-icon"></i>
                        <p>
                            {{trans('Message')}}
                        </p>
                    </a>
                </li>

            </ul>
        </li>
    @endif
    @endif
</ul>
