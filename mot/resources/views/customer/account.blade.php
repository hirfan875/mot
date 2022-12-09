@extends('web.layouts.app')
@section('content')
    <style>
        #imageUpload1
{
    display: none;
}

#profileImage1
{
    cursor: pointer;
}

#profile-container1 {
    width: 150px;
    height: 150px;
    overflow: hidden;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    border-radius: 50%;
}

#profile-container1 img {
    width: 150px;
    height: 150px;
}
        input[type="date"]:before {
            content: attr(placeholder) !important;
            color: #aaa;
            margin-right: 0.5em;
        }
        input[type="date"]:focus:before,
        input[type="date"]:valid:before {
            content: "";
        }
        
        
    </style>
    <!--=================
  Start breadcrumb
  ==================-->
    <div class="breadcrumb-container">
        <h1>{{__('breadcrumb.edit_account')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">{{__('breadcrumb.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{__('breadcrumb.edit_account')}}</li>
        </ol>
    </div>
    <!--=================
      End breadcrumb
      ==================-->

    <div class="container">
        <div class="my-account white-bg mt-minus">
            <div class="row">
                <div class="col-sm-3 user-sidebar">
                    <div class="nav flex-column nav-pills text-center user-nav pt-5" id="sidebar-admin" role="tablist" aria-orientation="vertical">
                        @include('customer.account-partial.profile-image')
                        @include('customer.account-partial.navigation', ['active'=>'my-account'])
                    </div>
                </div>

                <div class="col-sm-9 brder-left">
                    @include('customer.account-partial.feedback')
                    <div class="tab-content">
                        <!-- Tab Edit Account -->
                    @include('customer.account-partial.edit-profile')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
        $('#example').popover('show');

    </script>
@endsection
