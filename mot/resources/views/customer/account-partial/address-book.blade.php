<div class="tab-pane fade active show" id="addressbook" role="tabpanel" aria-labelledby="user-users-tab">
    <!--=================
    Start Book Address
    ==================-->

    <form class="account-form">
        <div class="form-header p-3">
            <h2 class="mb-0">{{__('Addresses')}}</h2>
        </div>
        <div class="form-body billing">
            <div class="row">
                @if($addresses->count() > 0)
                @if(isset($addresses))
                    @foreach($addresses as $address)
                        <div class="col-md-4 mb-4 border-bottom pb-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="{{$address->name}}" name="example">
                                <label class="custom-control-label" for="{{$address->name}}">{{$address->name}}</label>
                            </div>
                            <hr>
                            <p><span>{{$address->name}}</span></p>
                            <p>{{$address->email}}</p>
                            <p>{{$address->phone}}</p>
                            <p>{{$address->address}}</p>
                            <p>{{ isset($address->cities->title) ? $address->cities->title : $address->city }}</p>
                            <p>{{isset($address->states->title) ? $address->states->title : $address->state}}</p>
                            <p>{{$address->zipcode}}</p>
                            <p>{{isset($address->countries->title) ? $address->countries->title : $address->country}}</p>
                            <a class="btn-edit" href="javascript:;" onclick="update_profile({{$address->id}})">{{__('Edit')}}</a>
                            <a class="btn-delete" href="javascript:;" onclick="deleteAddress({{ $address->id }})">{{__('Delete')}}</a>
                        </div>
                    @endforeach
                    @endif
                @else
                    <div class="form-group col-md-12">
                        <div class="no_adrress_found text-center">
                            <img  alt="address_found" src="{{ cdn_url('/assets/frontend/assets/img') }}/no-address_found.svg"> 
                        </div>
                        <p class="text-center">{{__('No address found please add address')}}</p>
                    </div> 
                @endif

            </div>
            <div class="form-row">
                  
                <div class="form-group col-md-3 m-auto text-center">
                 
                    <a class="btn btn-primary d-inline-block delivery-here mt-5" data-toggle="modal" data-target="#address-form-modal">+ {{__('ADD ADDRESS')}}</a>
                </div>
            </div>
        </div>
    </form>
    <form id="delete-form" method="POST" style="display: none" >
        @method('POST')
        @csrf
        <input type="text" name="addressid" id="addressid" value="">
        <button type="submit"><span id="spinner"></span></button>
    </form>

    <!--=================
    End Book Address
    ==================-->
</div>
