<h3>{{ __('SEO Settings') }}</h3>
<div class="form-group">
  <label for="meta_title">{{ __('Meta Title') }}</label>
  <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $meta_title) }}">
</div>
<div class="form-group">
  <label for="meta_desc">{{ __('Meta Description') }}</label>
  <input type="text" name="meta_desc" id="meta_desc" class="form-control" value="{{ old('meta_desc', $meta_desc) }}">
</div>
<div class="form-group">
  <label for="meta_keyword">{{ __('Meta Keyword') }}</label>
  <input type="text" name="meta_keyword" id="meta_keyword" class="form-control" value="{{ old('meta_keyword', $meta_keyword) }}">
</div>