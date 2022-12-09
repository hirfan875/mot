@extends('admin.layouts.app')

@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.request.products') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
<div class="container-fluid">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <x-admin.back-button :url="route('admin.request.products')" />
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th width="28%" class="border-top-0">{{ __('requestproducts #') }}</th>
                                    <td width="72%" class="border-top-0">{{ $row->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Request Products Date/Time') }}</th>
                                    <td>{{ $row->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <td>{{ $row->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $row->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Phone') }}</th>
                                    <td>{{ $row->phone }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Type') }}</th>
                                    <td>{{ $row->product_type }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Product Name') }}</th>
                                    <td>{{ $row->prod_name }}</td>
                                </tr>
                                <tr>
                                    <th valign="top">{{ __('Product Description') }}</th>
                                    <td valign="top">{!! nl2br($row->prod_desc) !!}</td>
                                </tr>
                                <tr>
                                    <th valign="top">{{ __('Product Link') }}</th>
                                    <td valign="top">{!! $row->link !!}</td>
                                </tr>
                                <tr>
                                    <th valign="top">{{ __('Available Image') }}</th>
                                    <td class="text-left">
                                        <img src="{{ asset('/storage/'.$row->image) }}" alt="" width="200">
                                    </td>
                                </tr>
                            </table>
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
@endpush
