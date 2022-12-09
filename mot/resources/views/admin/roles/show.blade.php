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
                        <x-admin.back-button :url="route('admin.roles.index')" />
                    </div>
                    <div class="card-body">

                        <div class="dd">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    {{ $role->name }}
                                </div>
                            </div>
                            <ul class="dd_list">
                                <strong>Permissions:</strong>
                                @if(!empty($rolePermissions))
                                @foreach($rolePermissions as $v)
                                <li data-id="{{ $v->id }}">
                                    <div class="item">
                                        <span>{{ ucwords(str_replace("-", " ", $v->name )) }}</span>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                            </ul>
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
