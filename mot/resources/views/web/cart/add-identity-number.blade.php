 <!--The Modal-->
<div class="modal mt-5" id="myModal">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
             <!--Customer Identity Number' Modal body-->
            <div class="modal-body">
                <form class="" id="add-identity-number" method="POST" action="{{route('add-identity-number')}}">
                    @csrf
                    <div class="form-header p-3">
                        <h2 class="mb-0" id="address-heading">{{__('Customer Identity Number')}}</h2>
                    </div>
                    <div class="form-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <input type="text" name="identity_number" id="identity_number" class="form-control @error('identity_number') is-invalid @enderror" required placeholder="{{__('Identity Number')}}">
                                @error('identity_number')
                                <span class="invalid-feedback d-block" role="alert"> <strong>{{__($message)}}</strong> </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-primary delivery-here">{{__('Save')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
