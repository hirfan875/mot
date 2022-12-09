<a class="{{ ($active == 'my-account') ? "show active" : ""}} nav-link user-nav-item user-sidebar-item d-none d-md-block d-lg-block" href="{{route('my-account')}}">{{__('Edit Account')}}</a>
<a class="{{ ($active == 'change-password') ? "show active" : ""}} nav-link user-nav-item user-sidebar-item d-none d-md-block d-lg-block" href="{{route('change-password')}}" >{{__('Change Password')}}</a>
<a class="{{ ($active == 'address') ? "show active" : ""}} nav-link user-nav-item user-sidebar-item d-none d-md-block d-lg-block" href="{{route('list-address')}}" >{{__('Address Book')}}</a>

<a class="{{ ($active == 'wishlist') ? "show active" : ""}} nav-link user-nav-item user-sidebar-item d-none d-md-block d-lg-block" href="{{route('wishlist')}}" >{{__('Wish List')}}</a>

<a class="{{ ($active == 'order-history') ? "show active" : ""}} nav-link user-nav-item user-sidebar-item d-none d-md-block d-lg-block" href="{{route('order-history')}}" >{{__('Order History')}}</a>
<a class="nav-link user-nav-item user-sidebar-item d-none d-md-block d-lg-block" href="{{route('logout')}}" >{{__('Logout')}}</a>
<div class="dropdown  d-none pl-3 pr-3 " style="overflow:visible;">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    {{__('Select Account')}}
  </button>
  <div class="dropdown-menu d-none" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="{{route('my-account')}}">{{__('Edit Account')}}</a>
    <a class="dropdown-item" href="{{route('change-password')}}" >{{__('Change Password')}}</a>
    <a class="dropdown-item" href="{{route('list-address')}}" >{{__('Address Book')}}</a>

    <a class="dropdown-item" href="{{route('wishlist')}}" >{{__('Wish List')}}</a>
    <a class="dropdown-item" href="{{route('order-history')}}" >{{__('Order History')}}</a>
    <a class="dropdown-item" href="{{route('logout')}}" >{{__('Logout')}}</a>
  </div>
</div>

