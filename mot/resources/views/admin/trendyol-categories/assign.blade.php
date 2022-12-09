<div class="modal-header">
    <h5 class="modal-title">{{ __('Assign Category') }} </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="table-responsiev">
        <div class="card-body">
            <!-- alerts -->
            <x-alert class="alert-success" :status="session('success')" />
            <x-alert class="alert-danger" :status="session('error')" />
            <form action="{{ route('admin.trendyol.categories.assign', ['trendyol' => $trendyol]) }}" method="POST" enctype="multipart/form-data" id="add_form">
                @csrf
                @php
                    $parent_id = old('parent_id')
                @endphp
                <div class="form-group">
                    <label for="parent_id">{{ __('Categories') }}</label>
                    <input type="hidden" name="trendyol_cat_id" value="{{$trendyol}}">
                    <select name="category_id" id="category_id" class="custom-select js-basic-single">
                        <option value="">--{{ __('none') }}--</option>
                        @foreach ($categories as $r)
                        <option value="{{ $r->id }}" @if ($r->id == $parent_id) selected @endif>{{ $r->title }}</option>
                        @include('admin.includes.subcategories-options', ['subcategories' => $r->subcategories, 'level' => 1, 'parent_id' => $parent_id]);
                        @endforeach
                    </select>
                </div>
                <div class="text-center">
                    <!-- submit button -->
                    <x-admin.publish-button />
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
    
    $(document).ready(function() {
        $('.js-basic-single').select2();
    });
</script>