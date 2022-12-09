@extends('seller.layouts.app')

@section('content')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('seller.store.questions') }}">{{ $section_title }}</a></li>
    <li class="breadcrumb-item active">{{ $title }}</li>
  </ol>
  <div class="container-fluid">
    <div class="animated fadeIn">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              {{ $title }}
              <x-admin.back-button :url="route('seller.store.questions')" />
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th width="28%" class="border-top-0">{{ __('Question #') }}</th>
                    <td width="72%" class="border-top-0">{{ $row->id }}</td>
                  </tr>
                  <tr>
                    <th>{{ __('Question Date/Time') }}</th>
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
                    <td valign="top">{!! nl2br($row->message) !!}</td>
                  </tr>
                </table>
              </div>
              <h3>{{ __('Reply') }}</h3>
              <form action="{{ route('seller.store.questions.reply', ['question' => $row->id]) }}" method="post" id="reply_form">
                @csrf
                <div class="form-group">
                  <label for="subject">{{ __('Subject') }}</label>
                  <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $row->subject) }}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                  @error('subject')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="message">{{ __('Message') }}</label>
                  <textarea name="message" id="message" cols="30" rows="7" class="form-control" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">{!! old('message') !!}</textarea>
                  @error('message')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                  @enderror
                </div>
                <div class="text-center">
                  <button type="submit" class="btn btn-success">{{ __('Send') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>

          <div class="col-lg-6">
              <div class="card">
                  <div class="card-header">
                      {{__('Replies')}}
                  </div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table class="table">
                              <tr>
                                  <th width="28%" class="border-top-1">{{ __('Subject') }}</th>
                                  <th width="28%" class="border-top-1">{{ __('Message') }}</th>
                                  <th width="28%" class="border-top-1">{{ __('Replied At') }}</th>
                              </tr>
                              @foreach ($replies as $reply)
                                  <tr>
                                      <td valign="top">{{$reply->subject}}</td>
                                      <td valign="top">{{$reply->message}}</td>
                                      <td valign="top">{{$reply->created_at->format('d F Y')}}</td>
                                  </tr>
                              @endforeach
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
