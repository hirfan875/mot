/*Daily dealls timer scripts starts*/
$(function() {
  $('[data-countdown]').each(function() {
    var $this = $(this),
      finalDate =  $(this).data('countdown');

      // Set the date we're counting down to
      var countDownDate = new Date(finalDate).getTime();
      var elmId = $(this).attr("id");

// Update the count down every 1 second
      var x = setInterval(function() {

          let date = new Date;
          let strTime = date.toLocaleString("en-US", {
              timeZone: `${'Europe/Istanbul'}`
          });
          // Get today's date and time

          var now = new Date(strTime).getTime();
          var distance = countDownDate - now;

          // Time calculations for days, hours, minutes and seconds
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Output the result in an element with id="demo"
          document.getElementById(elmId).innerHTML = hours + ":" + minutes + ":" + seconds ;

          // If the count down is over, write some text
          if (distance < 0) {
              clearInterval(x);
              let expiredMessage = $('#expired-message').text();
              document.getElementById(elmId).innerHTML = expiredMessage;
//              document.getElementById("detail_"+elmId).style.display = "none";
              $("#detail_"+elmId).hide();
          }
      }, 1000);

  });

});
/*Daily dealls timer scripts ends*/
function updateTopCart(count) {
    if (/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $("#cart_block_mobile").load(location.href + " #cart_block_mobile");
    }
    $("#cart_block_web").load(location.href + " #cart_block_web");
    if (count == 0){
        $("#next-step-check").hide();
    }
}

//when click on add to cart
function addToCart(product_id, quantity, title, price ){
    if(quantity <= 0)
    {
        let stockMessage = $('#out-of-stock').text();
        ShowFailureModal(stockMessage);
        return false;
    }
//        checkQuantityAvailability(product_id, quantity);

    let CartData = {'product_id': product_id, 'quantity': 1};
    $('#loading-div-cart-'+product_id).removeClass('d-none');
    $('#loading-div-cart').removeClass('d-none');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: '/add-to-cart',
        data: CartData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(result){
            $('#loading-div-cart-'+product_id).addClass('d-none');
            let addCartMessage = $('#add-to-cart').text();
            ShowSuccessModal(addCartMessage);

            if(result['success'] == true)
            {
                updateTopCart(); //update top cart count and items
            }
        },
        error: function(result) {
             console.log(result);
             console.log(result.responseJSON.message);
            $('#loading-div-cart-'+product_id).addClass('d-none');
            if(result.responseJSON.message != ''){
                ShowFailureModal(result.responseJSON.message);
            } else {
               ShowFailureModal(result);
            }
        }
    });


        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });  
        window.dataLayer.push({
          event: "add_to_cart",
          ecommerce: {
            items: [
            {
              item_id: product_id,
              item_name: title,
              currency: "TRY",
              price: price,
              quantity: quantity
            }
            ]
          }
        });

}

//function checkQuantityAvailability(product_id, qty){
//        //check if the selected quantity is available
//        let stock = $('#stock-'+product_id).val();
//        if(stock-qty <= 0)
//        {
//            ShowFailureModal("{{trans('More quantity of the product is not available.')}}");
//            return false;
//        }
//        return true;
//    }

/* show success modal toaster*/
function ShowSuccessModal($msg, $toasterTime=null)
{
    /* set toaster time if not available */
    if($toasterTime === null){
        $toasterTime = 3000;
    }

    $('.toaster-success-text').html($msg); //set toaster message
    $('#successToaster').removeClass('hide'); //show toaster
    $('#successToaster').addClass('show'); //show toaster

    /* hide toaster after specific time */
    setTimeout(function() {
        $('#successToaster').removeClass('show');
    }, $toasterTime);
}

/* show success modal toaster*/
function ShowFailureModal($msg, $toasterTime=null)
{
    /* set toaster time if not available */
    if($toasterTime === null){
        $toasterTime = 3000;
    }

    $('.toaster-failure-text').html($msg); //set toaster message
    $('#failureToaster').removeClass('hide'); //show toaster
    $('#failureToaster').addClass('show'); //show toaster

    /* hide toaster after specific time */
    setTimeout(function() {
        $('#failureToaster').removeClass('show');
    }, $toasterTime);
}

/*Remove top cart item */
function removeTopCartItem(id) {

    /*stop hide cart dropdown*/
    var e = window.event
    e.stopPropagation();
    var container = $('#remove-mini-cart-item-'+id);
    /*stop hide cart dropdown ends*/
    container.addClass('fa-refresh fa-spin');

    var item_id = id;
    let CartData = {'id': item_id};
    let removalCartMessage = $('#cart-removal-message').text();
    if (confirm(removalCartMessage)) {
        $.ajax({
            type: "POST",
            dataType: "json",
            data: CartData,
            url: "/remove-cart-item",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.success) {
                    let removeCartMessage = $('#remove-from-cart').text();
                    ShowSuccessModal(removeCartMessage);
                    $('.cart-item-' + item_id).fadeOut();
                    updateTopCart(result.data.cartItemsQuantity);
                }
            }, error: function (error) {
                container.removeClass('fa-refresh fa-spin');
                console.log(error);
            }
        });
    }
    container.removeClass('fa-refresh fa-spin');
}
/*close toaster onclick */
$('.close').click(function(){
    $('.toast').removeClass('show');
  });



  $(document).ready(function(){
    $(".thumbview ").hide();
    $(".thumbview").click(function(){
      $(".listview ").show();
      $(".thumbview ").hide();
    });
  });


  $(document).ready(function(){
    $(".listview").click(function(){
        $(".listview ").hide();
        $(".thumbview ").show();
    });
  });

/* empty cart */
function emptyCart() {

    /*stop hide cart dropdown*/
    var e = window.event
    e.stopPropagation();
    let container = $('#empty-cart');
    /*stop hide cart dropdown ends*/

    let removalCartMessage = $('#empty-cart').attr('data-confirm-message');
    if (confirm(removalCartMessage)) {

        container.html('<i class="fa fa-refresh fa-spin"></i>');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/empty-cart",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if (result.success) {
                    ShowSuccessModal($('#empty-cart').attr('data-success-message'));
                    updateTopCart()
                }
            }, error: function (error) {
                container.html('clear');
                console.log(error);
            }
        });
    }
    // container.html('clear');
}

function toggleSocialShare($id) {
    $('#social-share-'+$id).toggleClass('d-none');
    $('#social-share-mobile-'+$id).toggleClass('d-none');
    $('#social-share-list-'+$id).toggleClass('d-none');
}
