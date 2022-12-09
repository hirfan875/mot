@extends('web.layouts.app')
@section('content')
<!--=================
  Start breadcrumb
  ==================-->
@if($pages)
<div class="breadcrumb-container">

    <h1>{{isset($pages->page_translates) ? $pages->page_translates->title : $pages->title}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{isset($pages->page_translates) ? $pages->page_translates->title : $pages->title}}</li>
    </ol>
</div>
<!--=================
  End breadcrumb
  ==================-->
<div class="container">
    <div class="contact_us bg-white p-5 mt-minus">
        <div class="main_content">
            <!-- START SECTION CONTACT -->
            <div class="section pb_70">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            {!! $pages->page_translates ? $pages->page_translates->data : $pages->data !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END SECTION CONTACT -->
        </div>
    </div>
</div>
@endif
@endsection
