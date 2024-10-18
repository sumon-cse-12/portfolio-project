@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('Blog Details')}} @endsection

@section('css')
<style>
    .content-blog {
    padding-top: 0px;
}
</style>
@endsection
@section('main-section')
<div class="content-blog">
    <div class="container">
      <div class="blog-content mt-5 mb-5">
        <div class="row">
            <div class="col-12">
                <div class="blog-details-title-sec">
                    Blog Details
                </div>
            </div>
        </div>
        <div class="row">
       
            @if (isset($blog) && $blog)
        <div class="col-sm-12 col-lg-12 blog-content-data">
                <div class="blog-img">
                    <a href="{{route('blog.details',[$blog->slug])}}"><img src="{{asset('uploads/'.$blog->image)}}" alt=""
                            class="img-fluid"></a>
                </div>
                <div class="blog-title">
                    {{$blog->title}}
                </div>
                <div class="blog-information">
                    <div class="body-info">
                        @if($blog->description)
                        {!! $blog->description !!}
                        @endif
                    </div>
                </div>
        </div>
        @endif
            {{-- <div class="col-lg-4 d-none">
                <div class="recent-blogs-list">
                    @if(isset($recent_blogs) && $recent_blogs)
                   @foreach($recent_blogs as $recent_blog)
                   <div class="mb-3 mt-3">
                    <a href="{{route('blog.details',[$recent_blog->slug])}}" class="recent-blog-link">
                        <div class="blog-list-sec d-flex align-items-center">
                            <div class="recent-blog-image">
                               @if($recent_blog->image)
                               <img src="{{asset('uploads/'.$recent_blog->image)}}" class="recent-blog-img" alt="">
                               @endif
                            </div>
                            <div class="recent-blog-contnet-sec ml-3">
                                @php
                                $blog_title = strip_tags($recent_blog->title);
                                $max_length = 50;
                                $truncated_title = mb_substr($blog_title, 0, $max_length, 'UTF-8');
                                
                                if (mb_strlen($blog_title, 'UTF-8') > $max_length) {
                                    $truncated_title = mb_substr($truncated_title, 0, mb_strrpos($truncated_title, ' ', 0, 'UTF-8'));
                                    $truncated_title .= '...';
                                }
                                @endphp
                                <div class="recent-blog-title">
                                    {{$truncated_title}}
                                </div>
                                <div class="blog-published-date">
                                    <i class="fa fa-calendar"></i> {{$recent_blog->created_at}}
                                </div>
                            </div>
                        </div>
                    </a>
                   </div>
                   @endforeach
                   @endif
                </div>
            </div> --}}
            
        </div>
    </div>

    </div>
</div>
@endsection
