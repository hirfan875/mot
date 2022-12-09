@extends('admin.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.contact.inquiries') }}">{{ __('Contact Inquiries') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.contact.inquiries.view.archived') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-7">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('admin.contact.inquiries.view.archived')" />
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th width="28%" class="border-top-0">{{ __('Inquiry #') }}</th>
                    <td width="72%" class="border-top-0">{{ $row->id }}</td>
                  </tr>
                  <tr>
                    <th>{{ __('Inquiry Date/Time') }}</th>
                    <td>{{ $row->created_at }}</td>
                  </tr>
                  <tr>
                    <th>{{ __('Status') }}</th>
                    <td>
                      @if ( $row->status == 2 )
                        {{ __('Viewed') }}
                      @else
                        {{ __('New') }}
                      @endif
                    </td>
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
                    <th valign="top">{{ __('Message') }}</th>
                    <td valign="top">{!! nl2br($row->data) !!}</td>
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