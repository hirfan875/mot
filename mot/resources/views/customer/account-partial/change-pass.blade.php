<div class="tab-pane fade active show" id="changepass" role="tabpanel" aria-labelledby="user-communicate-tab">
    <!--=================
    Start Change Pass
    ==================-->
    <form class="account-form" id="change-password"  method="POST" action="{{route('change-password')}}">
        @csrf
        <div class="form-header p-3">
            <h2 class="mb-0">{{__('Change Password')}}</h2>
        </div>
        <div class="form-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <input type="password" name="password" class="form-control"  placeholder="{{__('Enter New Password')}}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" data-toggle="tooltip" title="{{__('Password must be 8 digits & Alpha-numeric with Upper & lower case i.e ABcd1234')}}">
                    @error('password')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <input type="password" name="password_confirmation"  class="form-control" placeholder="{{__('Re-Enter New Password')}}" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')" >
                    @error('password_confirmation')
                    <span class="invalid-feedback d-block" role="alert"> <strong>{{ __($message) }}</strong> </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <button type="submit" class="btn btn-primary delivery-here">{{__('CHANGE PASSWORD')}}</button>
                </div>
            </div>
        </div>
    </form>
    <!--=================
    End Change Pass
    ==================-->
</div>

