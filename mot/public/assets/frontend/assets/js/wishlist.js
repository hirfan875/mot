$("body").delegate(".wishlist", "click", function (e) {
    let action = $(this).attr('data-action');
    let id = $(this).attr('data-id');
    $('div#loading-div-' + id)
            .removeClass('d-none')
            .addClass('loading-div');

    if (action == 'add') {
        addWishList(id);
        return;
    }

    removeWishList(id);
});

function addWishList(id) {
    let ent_id = id;

    $.ajax({
        type: "GET",
        dataType: "json",
        url: '/add-to-wishlist' + '/' + ent_id,

        success: function (data) {

            $('.top-wishlist-count').text(data.data.totalCount);
            $('i#wishlist-sec-' + id)
                    .removeClass()
                    .addClass('fa fa-heart');
            $('#add-' + id).attr("data-action", "remove");
            $('div#loading-div-' + id).removeClass('loading-div').addClass('d-none');
            // show message from server .. js message cant be translated
            ShowSuccessModal(data.data.message, 2000);
            
            add_to_wishlist(id,data.data.product.title,data.data.product.price);

        },
    });
}

function removeWishList(id) {

    let removalMessage = $('#wishlist-removal-message').text();
    let ent_id = id;
    if (confirm(removalMessage))
    {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/remove-from-wishlist' + '/' + ent_id,
            success: function (data) {
                console.log(data.data.totalCount);
                if (data.data.totalCount == 0) {
                    $("#wshlst").load(location.href + " #wshlst");
                } else {
                    $(".top-wishlist-count").text(data.data.totalCount);
                }
                $('i#wishlist-sec-' + id)
                        .removeClass()
                        .addClass('icon-heart');
                $('#add-' + id).attr("data-action", "add");
                $("#wshlst").load(location.href + " #wshlst");
                $("#wshlst-web").load(location.href + " #wshlst-web");
                $("#wshlst-mob").load(location.href + " #wshlst-mob");
                $('div#loading-div-' + id).removeClass('loading-div').addClass('d-none');
                ShowSuccessModal(data.data.message, 2000);
                refreshWishlistTable();
            }
        });
    }
    function refreshWishlistTable() {
        $("#wishlist-table").load(location.href + " #wishlist-table");
    }
    $('div#loading-div-' + id).removeClass('loading-div').addClass('d-none');

}

function  add_to_wishlist(id,title,price) {
    gtag("event", "add_to_wishlist", {
        currency: "Try",
        value: price,
        items: [
            {
                item_id: id,
                item_name: title,

            }
        ],
    });
}

// setInterval(function(){
//     $('#loading-div-'+id).removeClass('d-none')
//   });

// $(".wishlist-sec-"+uuid).addClass('fa fa-heart');
// return false;
