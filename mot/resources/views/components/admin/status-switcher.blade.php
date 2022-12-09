@props(['id', 'name' => 'status', 'value'])
<label class="switch switch-label switch-pill switch-success">
  <input class="switch-input" name="{{ $name }}" type="checkbox" value="{{ $value }}" @if ( $value ) checked @endif >
  <span class="switch-slider" data-value="{{ $value }}" data-id="{{ $id }}" data-checked="✓" data-unchecked="✕" onclick="ChangeSwitch(this)"></span>
</label>