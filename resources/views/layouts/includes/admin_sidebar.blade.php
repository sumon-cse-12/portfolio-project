<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    <!--Dashboard  -->
    <li class="nav-item mb-2">
        <a href="{{route('admin.dashboard')}}" class="nav-link {{isSidebarActive('admin.dashboard')}}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                {{trans('Dashboard')}}
            </p>
        </a>
    </li>


    <li class="custom-menu-li">
        <b>MENUS</b>
    </li>
    <li class="nav-item">
        <a href="{{route('admin.theme.slider.index')}}" class="nav-link {{isSidebarActive('admin.theme.slider.index')}}">
            <i class="nav-icon fa fa-comment n-primary-c"></i>
            <p>
                {{trans('Home Slider')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.welcome.section')}}" class="nav-link {{isSidebarActive('admin.welcome.section')}}">
            <i class="nav-icon fa fa-hashtag n-warning-c"></i>
            <p>
                {{trans('admin.welcome_section')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.services')}}" class="nav-link {{isSidebarActive('admin.services')}}">
            <i class="nav-icon fa fa-info n-success-c"></i>
            <p>
                {{trans('admin.services')}}
            </p>
        </a>
    </li>

    <li class="nav-item has-treeview">
        <a href="" class="nav-link {{isSidebarActive('admin.instruments.index' , 'admin.instruments.create')}}">
            <i class="nav-icon fas fa-plus n-info-c"></i>
            <p>
                {{trans('admin.instruments')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{route('admin.instruments.index')}}" class="nav-link {{isSidebarActive('admin.instruments.index')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('admin.list')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('admin.instruments.create')}}" class="nav-link {{isSidebarActive('admin.instruments.create')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('admin.create')}}
                    </p>
                </a>
            </li>

        </ul>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.fees')}}" class="nav-link {{isSidebarActive('admin.fees')}}">
            <i class="nav-icon fas fa-sms n-info-c"></i>
            <p>
                {{trans('admin.fees')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.courses')}}" class="nav-link {{isSidebarActive('admin.courses')}}">
            <i class="nav-icon fa fa-bell n-warning-c"></i>
            <p>
                {{trans('admin.courses')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.omug')}}" class="nav-link {{isSidebarActive('admin.omug')}}">
            <i class="nav-icon fa fa-bullseye n-success-c"></i>
            <p>
                {{trans('admin.omug')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.resources')}}" class="nav-link {{isSidebarActive('admin.resources')}}">
            <i class="nav-icon fa fa-yin-yang n-warning-c"></i>
            <p>
                {{trans('admin.resources')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.teams')}}" class="nav-link {{isSidebarActive('admin.teams')}}">
            <i class="nav-icon fas fa-file-signature n-success-c"></i>
            <p>
                {{trans('admin.team')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.theme.contact.index')}}" class="nav-link {{isSidebarActive('admin.theme.contact.index')}}">
            <i class="nav-icon fa fa-comment n-primary-c"></i>
            <p>
                {{trans('Contact Us')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{ route('admin.sign.up.info')}}" class="nav-link {{isSidebarActive('admin.sign.up.info')}}">
            <i class="nav-icon fa fa-info-circle n-info-c"></i>
            <p>
                {{trans('admin.sign_up_info')}}
            </p>
        </a>
    </li>

    <li class="nav-item">
        <a href="{{route('admin.faq.index')}}" class="nav-link {{isSidebarActive('admin.faq*')}}">
            <i class="nav-icon fa fa-question n-success-c"></i>
            <p>
                {{trans('FAQ')}}
            </p>
        </a>
    </li>


    <!--Senders  -->
    {{-- Blog Management --}}
    <li class="nav-item has-treeview ">
        <a href="" class="nav-link {{isSidebarTrue(['admin.blog-category.*','admin.bloglist.*'])? 'active nav-link-active' : ''}}">

            <i class="nav-icon fas fa-sms n-warning-c"></i>
            <p>
                 {{trans('admin.blog')}} {{trans('admin.management')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['admin.blog-category.index','admin.bloglist.index'])? 'block': 'none'}};">


            <li class="nav-item">
                <a href="{{route('admin.blog-category.index')}}" class="nav-link {{isSidebarActive('admin.blog-category.index')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('admin.blog')}} {{trans('admin.category')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('admin.bloglist.index')}}" class="nav-link {{isSidebarActive('admin.bloglist.index')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('admin.blog')}} {{trans('admin.list')}}
                    </p>
                </a>
            </li>

        </ul>
    </li>
    {{-- Publications Management --}}
    <li class="nav-item has-treeview ">
        <a href="" class="nav-link {{isSidebarTrue(['admin.category-publication.*','admin.publications.*'])? 'active nav-link-active' : ''}}">

            <i class="nav-icon fas fa-sms n-warning-c"></i>
            <p>
              {{trans('Publication Management')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview"
            style="display: {{isSidebarTrue(['admin.category-publication.index','admin.publications.index'])? 'block': 'none'}};">


            <li class="nav-item">
                <a href="{{route('admin.category-publication.index')}}" class="nav-link {{isSidebarActive('admin.category-publication.index')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('Category')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('admin.publications.index')}}" class="nav-link {{isSidebarActive('admin.publications.index')}}">
                    <i class="fa fa-angle-double-right nav-icon"></i>
                    <p>
                        {{trans('admin.list')}}
                    </p>
                </a>
            </li>

        </ul>
    </li>


    <li class="nav-item">
        <a href="{{route('admin.settings.index')}}" class="nav-link {{isSidebarActive('admin.settings.index')}}">
            <i class="nav-icon fas fa-cog n-primary-c"></i>
            <p>
                {{trans('Application Settings')}}
            </p>
        </a>
    </li>

</ul>
