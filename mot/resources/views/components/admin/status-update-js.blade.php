@props(['url'])
<script type="text/javascript">
  function ChangeSwitch( cb ){

    let id = $(cb).data('id');

    if ( $(cb).data('value') == 1 ) {
      $(cb).data('value', 0);
      var status = 0; // inactive
    } else {
      $(cb).data('value', 1);
      var status = 1; // active
    }

    // send ajax request
    axios.post('{{ $url }}', {
      id: id,
      value: status
    });
  }
</script>
