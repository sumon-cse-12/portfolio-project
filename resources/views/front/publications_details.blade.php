@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('Publication Details')}} @endsection

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
                    Publication Details
                </div>
            </div>
        </div>
        <div class="row">
       
            @if (isset($publication) && $publication)
        <div class="col-sm-12 col-lg-12 blog-content-data">
                <div class="blog-img">
                    <a href="{{route('publications.details',[$publication->slug])}}"><img src="{{asset('uploads/'.$publication->image)}}" alt=""
                            class="img-fluid"></a>
                </div>
                <div class="blog-title">
                    {{$publication->title}}
                </div>
                <div class="blog-information">
                    <div class="body-info">
                        @if($publication->description)
                        {!! $publication->description !!}
                        @endif
                    </div>
                </div>
        </div>
        @endif
            {{-- <div class="col-lg-4 d-none">
                <div class="recent-blogs-list">
                    @if(isset($recent_publications) && $recent_publications)
                   @foreach($recent_publications as $recent_publication)
                   <div class="mb-3 mt-3">
                    <a href="{{route('publications.details',[$recent_publication->slug])}}" class="recent-blog-link">
                        <div class="blog-list-sec d-flex align-items-center">
                            <div class="recent-blog-image">
                               @if($recent_publication->image)
                               <img src="{{asset('uploads/'.$recent_publication->image)}}" class="recent-blog-img" alt="">
                               @endif
                            </div>
                            <div class="recent-blog-contnet-sec ml-3">
                                @php
                                $publication_title = strip_tags($recent_publication->title);
                                $max_length = 50;
                                $truncated_title = mb_substr($publication_title, 0, $max_length, 'UTF-8');
                                
                                if (mb_strlen($publication_title, 'UTF-8') > $max_length) {
                                    $truncated_title = mb_substr($truncated_title, 0, mb_strrpos($truncated_title, ' ', 0, 'UTF-8'));
                                    $truncated_title .= '...';
                                }
                                @endphp
                                <div class="recent-blog-title">
                                    {{$truncated_title}}
                                </div>
                                <div class="blog-published-date">
                                    <i class="fa fa-calendar"></i> {{$recent_publication->created_at}}
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
