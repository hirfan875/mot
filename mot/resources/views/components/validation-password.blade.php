<script type="text/javascript">
  $.validator.addMethod("passwordcheck", function(value, element) {
    return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value);
  }, '{{ __('Password must contain one uppercase letter, one lowercase letter and one numerical') }}');
</script>
