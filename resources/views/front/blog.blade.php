@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('Blogs')}} @endsection

@section('main-section')
<div class="content-blog">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="all-blog-title">
                    Blogs
                </div>
            </div>
        </div>
      <div class="blog-content mt-5 mb-5">
        <div class="row">
           
            @if (isset($blogs) && $blogs)
              @foreach ($blogs as $blog)
              <div class="col-lg-6 col-md-6 col-12 blog-content-data">
                <div class="blog-image">
                    @if (isset($blog->image) && $blog->image)
                    <a href="{{route('blog.details',[$blog->slug])}}">
                        <img src="{{asset('uploads/'.$blog->image)}}" alt="" class="blog-sec-img zoom-effect img-fluid"></a>
                    @endif
                </div>
                <div class="blog-information">
                    <div class="head-info">
                        {{$blog->created_at->format('M d')}}
                    </div>
                    <div class="body-info">
                        @php
                        $blog_title = strip_tags($blog->title);
                        $max_length = 70;
                        $truncated_title = mb_substr($blog_title, 0, $max_length, 'UTF-8');
                        
                        if (mb_strlen($blog_title, 'UTF-8') > $max_length) {
                            $truncated_title = mb_substr($truncated_title, 0, mb_strrpos($truncated_title, ' ', 0, 'UTF-8'));
                            $truncated_title .= '...';
                        }
                    @endphp
                       <h2> <a href="{{route('blog.details',[$blog->slug])}}">
                               {{$truncated_title}}
                        </a>
                    </h2>
                    <div class="blog-details-sec">
                        @php
                            $description = strip_tags($blog->description);
                            $max_length = 170;
                            $truncated_description = mb_substr($description, 0, $max_length, 'UTF-8');
                            
                            if (mb_strlen($description, 'UTF-8') > $max_length) {
                                $truncated_description = mb_substr($truncated_description, 0, mb_strrpos($truncated_description, ' ', 0, 'UTF-8'));
                                $truncated_description .= '...';
                            }
                        @endphp
                        {!! $truncated_description !!}
                        @if (mb_strlen($description, 'UTF-8') > $max_length)
                            <a href="{{ route('blog.details', [$blog->slug]) }}">read more</a>
                        @endif
                    </div>
                    
                    </div>
                </div>
            </div>
              @endforeach
            @endif
        </div>
    </div>

    </div>
</div>
@endsection
