@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('Publications')}} @endsection

@section('main-section')
<div class="content-blog">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="all-blog-title">
                    Publications
                </div>
            </div>
        </div>
      <div class="blog-content mt-5 mb-5">
        <div class="row">
           
            @if (isset($publications) && $publications)
              @foreach ($publications as $publication)
              <div class="col-lg-6 col-md-6 col-12 blog-content-data">
                <div class="blog-image">
                    @if (isset($publication->image) && $publication->image)
                    <a href="{{route('publications.details',[$publication->slug])}}">
                        <img src="{{asset('uploads/'.$publication->image)}}" alt="" class="blog-sec-img zoom-effect img-fluid"></a>
                    @endif
                </div>
                <div class="blog-information">
                    <div class="head-info">
                        {{$publication->created_at->format('M d')}}
                    </div>
                    <div class="body-info">
                        @php
                        $publication_title = strip_tags($publication->title);
                        $max_length = 70;
                        $truncated_title = mb_substr($publication_title, 0, $max_length, 'UTF-8');
                        
                        if (mb_strlen($publication_title, 'UTF-8') > $max_length) {
                            $truncated_title = mb_substr($truncated_title, 0, mb_strrpos($truncated_title, ' ', 0, 'UTF-8'));
                            $truncated_title .= '...';
                        }
                    @endphp
                       <h2> <a href="{{route('publications.details',[$publication->slug])}}">
                               {{$truncated_title}}
                        </a>
                    </h2>
                    <div class="blog-details-sec">
                        @php
                            $description = strip_tags($publication->description);
                            $max_length = 170;
                            $truncated_description = mb_substr($description, 0, $max_length, 'UTF-8');
                            
                            if (mb_strlen($description, 'UTF-8') > $max_length) {
                                $truncated_description = mb_substr($truncated_description, 0, mb_strrpos($truncated_description, ' ', 0, 'UTF-8'));
                                $truncated_description .= '...';
                            }
                        @endphp
                        {!! $truncated_description !!}
                        @if (mb_strlen($description, 'UTF-8') > $max_length)
                            <a href="{{ route('publications.details', [$publication->slug]) }}">read more</a>
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
