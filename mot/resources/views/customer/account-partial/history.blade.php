<div class="tab-pane fade active show" id="history" aria-labelledby="user-requests-tab">
    <div class="table-content table-responsive mb-45 tbUser">
        <div class="container">
        
        
        <div class="row">
                <div class="col-md-12"><h2 class="mt-4 mb-3">{{__('Order History')}}</h2></div>
            </div>
            <div class="row mb-4 s_history">
                 @if($orders->count() > 0 )
                <form action="{{url('history')}}" method="GET" id="order_filters">
                </form>
                <div class="col-md-12 mt-3">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified historyTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#Paid">{{__('Paid')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Ready-To-Be-Shipped">{{__('Ready To Ship')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Shipped">{{__('Shipped')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Delivered">{{__('Delivered')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Return">{{__('Cancellation Requested')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Cancelled">{{__('Cancelled')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#Return">{{__('Returned')}}   </a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div id="Paid" class=" tab-pane active"><br>
                            
                            @if($orders->count() > 0 )
                            <!--order List-->
                            <div class="order_history_card_outer">
                                <div class="order_history_card">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col" width="70">{{__('Qty')}}</th>
                                                    <th scope="col">{{__('Order Number')}}</th>
                                                    <th scope="col" width="180">{{__('Date')}}</th>
                                                    <th scope="col">{{__('Total')}}</th>
                                                    <th scope="col" width="200">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @if($order->getStatus()=='Paid')
                                                <tr>
                                                    <th scope="row">{{$order->getTotalQty()}}</th>
                                                    <td><a href="{{route('order-detail' ,$order->id) }}">{{$order->order_number}}</a></td>
                                                    <td> {{$order->getLastStatusUpdateDate()->format('d/m/Y')}}</td>
                                                    <td><span class="font-weight-normal  d-block"><b>{{__($order->currency)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency)}} &nbsp; </b> </span></td>
                                                    <td><a href="{{route('track-package' ,$order->id) }}" class="trackOrdr">Track</a> <a href="{{route('order-detail' ,$order->id) }}" class="viewOrdr" >{{__('View')}}</a></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
                            @else
                            
                            <div> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                            <img alt="history" src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            
                            @endif
                        </div>
                        <div id="Ready-To-Be-Shipped" class="container tab-pane fade"><br>
                            @if($orders->count() > 0)
                            <!--order List-->
                            <div class="order_history_card_outer">
                                <div class="order_history_card">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col" width="70">{{__('Qty')}}</th>
                                                    <th scope="col">{{__('Order Number')}}</th>
                                                    <th scope="col" width="180">{{__('Date')}}</th>
                                                    <th scope="col">{{__('Total')}}</th>
                                                    <th scope="col" width="200">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @if($order->getStatus()=='Ready To Ship')
                                                <tr>
                                                    <th scope="row">{{$order->getTotalQty()}}</th>
                                                    <td><a href="{{route('order-detail' ,$order->id) }}">{{$order->order_number}}</a></td>
                                                    <td><span class="active_green"></span>{{__($order->getStatus())}} {{$order->getLastStatusUpdateDate()->format('d m Y')}}</td>
                                                    <td><span class="font-weight-normal  d-block"><b>{{__($order->currency)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency)}} &nbsp; </b> </span></td>
                                                    <td><a href="{{route('track-package' ,$order->id) }}" class="trackOrdr">Track</a> <a href="{{route('order-detail' ,$order->id) }}" class="viewOrdr" >{{__('View')}}</a></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
                            @else
                            <div> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                            <img alt="history" src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            @endif
                        </div>
                        <div id="Shipped" class="container tab-pane fade"><br>
                            @if($orders->count() > 0)
                            <!--order List-->
                            <div class="order_history_card_outer">
                                <div class="order_history_card">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col" width="70">{{__('Qty')}}</th>
                                                    <th scope="col">{{__('Order Number')}}</th>
                                                    <th scope="col" width="180">{{__('Date')}}</th>
                                                    <th scope="col">{{__('Total')}}</th>
                                                    <th scope="col" width="200">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @if($order->getStatus()=='Shipped')
                                                <tr>
                                                    <th scope="row">{{$order->getTotalQty()}}</th>
                                                    <td><a href="{{route('order-detail' ,$order->id) }}">{{$order->order_number}}</a></td>
                                                    <td><span class="active_green"></span>{{__($order->getStatus())}} {{$order->getLastStatusUpdateDate()->format('d m Y')}}</td>
                                                    <td><span class="font-weight-normal  d-block"><b>{{__($order->currency)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency)}} &nbsp; </b> </span></td>
                                                    <td><a href="{{route('track-package' ,$order->id) }}" class="trackOrdr">Track</a> <a href="{{route('order-detail' ,$order->id) }}" class="viewOrdr" >{{__('View')}}</a></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
                            @else
                            <div> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                            <img alt="history" src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            @endif
                        </div>
                        <div id="Delivered" class="container tab-pane fade"><br>
                            @if($orders->count() > 0)
                            <!--order List-->
                            <div class="order_history_card_outer">
                                <div class="order_history_card">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col" width="70">{{__('Qty')}}</th>
                                                    <th scope="col">{{__('Order Number')}}</th>
                                                    <th scope="col" width="180">{{__('Date')}}</th>
                                                    <th scope="col">{{__('Total')}}</th>
                                                    <th scope="col" width="200">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @if($order->getStatus()=='Delivered')
                                                <tr>
                                                    <th scope="row">{{$order->getTotalQty()}}</th>
                                                    <td><a href="{{route('order-detail' ,$order->id) }}">{{$order->order_number}}</a></td>
                                                    <td><span class="active_green"></span>{{__($order->getStatus())}} {{$order->getLastStatusUpdateDate()->format('d m Y')}}</td>
                                                    <td><span class="font-weight-normal  d-block"><b>{{__($order->currency)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency)}} &nbsp; </b> </span></td>
                                                    <td><a href="{{route('track-package' ,$order->id) }}" class="trackOrdr">Track</a> <a href="{{route('order-detail' ,$order->id) }}" class="viewOrdr" >{{__('View')}}</a></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
                            @else
                            <div> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                            <img alt="history" src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            @endif
                        </div>
                        <div id="Return" class="container tab-pane fade"><br>
                            @if($orders->count() > 0)
                            <!--order List-->
                            <div class="order_history_card_outer">
                                <div class="order_history_card">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col" width="70">{{__('Qty')}}</th>
                                                    <th scope="col">{{__('Order Number')}}</th>
                                                    <th scope="col" width="180">{{__('Date')}}</th>
                                                    <th scope="col">{{__('Total')}}</th>
                                                    <th scope="col" width="200">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @if($order->getStatus()=='Cancellation Requested')
                                                <tr>
                                                    <th scope="row">{{$order->getTotalQty()}}</th>
                                                    <td><a href="{{route('order-detail' ,$order->id) }}">{{$order->order_number}}</a></td>
                                                    <td><span class="active_green"></span>{{__($order->getStatus())}} {{$order->getLastStatusUpdateDate()->format('d m Y')}}</td>
                                                    <td><span class="font-weight-normal  d-block"><b>{{__($order->currency)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency)}} &nbsp; </b> </span></td>
                                                    <td><a href="{{route('track-package' ,$order->id) }}" class="trackOrdr">Track</a> <a href="{{route('order-detail' ,$order->id) }}" class="viewOrdr" >{{__('View')}}</a></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
                            @else
                            <div> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                            <img alt="history" src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            @endif
                        </div>
                        <div id="Cancelled" class="container tab-pane fade"><br>
                            @if($orders->count() > 0)
                            <!--order List-->
                            <div class="order_history_card_outer">
                                <div class="order_history_card">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th scope="col" width="70">{{__('Qty')}}</th>
                                                    <th scope="col">{{__('Order Number')}}</th>
                                                    <th scope="col" width="180">{{__('Date')}}</th>
                                                    <th scope="col">{{__('Total')}}</th>
                                                    <th scope="col" width="200">{{__('Action')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                @if($order->getStatus()=='Cancelled')
                                                <tr>
                                                    <th scope="row">{{$order->getTotalQty()}}</th>
                                                    <td><a href="{{route('order-detail' ,$order->id) }}">{{$order->order_number}}</a></td>
                                                    <td><span class="active_green"></span>{{__($order->getStatus())}} {{$order->getLastStatusUpdateDate()->format('d m Y')}}</td>
                                                    <td><span class="font-weight-normal  d-block"><b>{{__($order->currency)}}&nbsp;{{convertTryForexRate($order->total, $order->forex_rate, $order->base_forex_rate, $order->currency)}} &nbsp; </b> </span></td>
                                                    <td><a href="{{route('track-package' ,$order->id) }}" class="trackOrdr">Track</a> <a href="{{route('order-detail' ,$order->id) }}" class="viewOrdr" >{{__('View')}}</a></td>
                                                </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                        </table>  
                                    </div>
                                </div>
                            </div>
                            @else
                            <div> 
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                            <img alt="history" src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            @endif
                        </div>
                        <div id="Return" class="container tab-pane fade">Return</div>
                    </div>
                </div>
                 @else
                            
                            <div class="col-md-12 mt-3">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="no_orderHistory">
                                            <div class="nm_img">
                                                <img src="{{ cdn_url('/assets/frontend/assets/img') }}/no_pro-history.png" alt="history" width="200">
                                            </div>
                                           <h3>{{__('No Purchase History')}} </h3>
                                           <p>{{__('heck back after your next shopping trip!')}}</p>
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            
                            @endif
<!--                <div class="col-md-3 d-none">
                    <select name="status[]" id="status" class="form-control" form="order_filters">
                        <option value="">{{__('Select Status')}}</option>
                        <option value="2" @if(isset(request()->status) && request()->status[0] == 2) selected @endif>{{__('Paid')}}</option>
                        <option value="4" @if(isset(request()->status) && request()->status[0] == 4) selected @endif>{{__('Shipped')}}</option>
                        <option value="5" @if(isset(request()->status) && request()->status[0] == 5) selected @endif>{{__('Delivered')}}</option>
                        <option value="7" @if(isset(request()->status) && request()->status[0] == 7) selected @endif>{{__('Cancelled')}}</option>
                        <option value="8" @if(isset(request()->status) && request()->status[0] == 8) selected @endif>{{__('Return Requested')}}</option>
                    </select>
                </div>
                <div class="col-md-3 d-none">
                    <input type="text" name="daterange" id="daterange" class="form-control">
                    <input type="hidden" name="start_date" id="start_date" form="order_filters">
                    <input type="hidden" name="end_date" id="end_date" form="order_filters">
                </div>
                <div class="col-md-3 d-none">
                    <button type="submit" class="btn btn-primary submitbtn" form="order_filters">{{__('Submit')}}</button>
                    <a href="{{url('history')}}" class="btn btn-info resetbtn" form="order_filters">{{__('Reset')}}</a>
                </div>-->
            </div>
        </div>
    </div>
</div>
<!-- History End -->

<!-- The Modal -->
