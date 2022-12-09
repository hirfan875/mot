@extends('admin.layouts.app')


@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <x-admin.back-button :url="route('admin.permission.index')" />
                    </div>
                    <div class="card-body">

                        <div class="dd">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    {{ $permission->name }}
                                </div>
                            </div>
                            
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('footer')
<x-validation />
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $("#add_form").validate();
    });
</script>
@endpush
