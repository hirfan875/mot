<!-- success toaster -->
<div id="successToaster" class="toast fade position-fixed">
  <div class="toast-header toast-success pt-3 pb-3">
    <strong class="mr-auto"><i class="fa fa-check-circle text-success fa-2x align-middle mr-1"></i> <span class="toaster-success-text"></span></strong>
    <button type="button"  class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
</div>
<!-- success toaster ends -->
<!-- failed toaster -->
<div id="failureToaster" class="toast fade position-fixed">
  <div class="toast-header toast-danger pt-3 pb-3">
    <strong class="mr-auto"><i class="fa fa-exclamation-circle text-danger fa-2x align-middle mr-1"></i> <span class="toaster-failure-text"></span></strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
</div>
<!-- failed toaster ends -->
@if(Session::has('success'))
<div class="toast fade show position-fixed">
  <div class="toast-header toast-success pt-3 pb-3">
    <strong class="mr-auto"><i class="fa fa-check-circle text-success fa-2x align-middle mr-1"></i> {{session('success')}}</strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
</div>
@endif
@if(Session::has('error'))
<div class="toast fade show position-fixed">
  <div class="toast-header toast-danger pt-3 pb-3">
    <strong class="mr-auto"><i class="fa fa-exclamation-circle text-danger fa-2x align-middle mr-1"></i> {{session('error')}}</strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
  </div>
</div>
@endif
