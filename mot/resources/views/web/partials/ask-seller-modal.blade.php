<div class="modal fade" id="askquestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('mot-products.ask_question_to')}} {{isset($store->store_profile_translates)? $store->store_profile_translates->name : $store->name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('store-question')}}">
                    @csrf
                    <div class="form-group" hidden>
                        <label for="recipient-name" class="col-form-label">{{__('Store Id')}}</label>
                        <input type="text" class="form-control" name="store_id" value="{{$store->id}}">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{__('Your Name')}}</label>
                        <input type="text" class="form-control" name="name" value="{{ Auth::guard('customer')->user() != null ? Auth::guard('customer')->user()->name : null }}" @if(Auth::guard('customer')->user() != null && Auth::guard('customer')->user()->name != null) {{'readonly'}} @endif  required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{__('Email')}}</label>
                        <input type="text" class="form-control" name="email" value="{{ Auth::guard('customer')->user() != null ? Auth::guard('customer')->user()->email : null }}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" @if(Auth::guard('customer')->user() != null && Auth::guard('customer')->user()->email != null) {{'readonly'}} @endif required>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{__('Phone')}}</label>
                        <input type="tel" class="form-control {{$errors->has('phone') ? 'is-invalid' : null}}" name="phone" value="{{ Auth::guard('customer')->user() != null ? Auth::guard('customer')->user()->phone : null }}" @if(Auth::guard('customer')->user() != null && Auth::guard('customer')->user()->phone != null) {{'readonly'}} @endif minlength="9" maxlength="13" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">{{__('Message')}}:</label>
                        <textarea class="form-control" name="message" required oninvalid="this.setCustomValidity('{{__('Please fill out this field')}}')"  oninput="this.setCustomValidity('')"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Send')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
