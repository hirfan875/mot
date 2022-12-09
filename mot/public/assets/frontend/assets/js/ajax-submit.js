// $('.review-form').on('submit', function(e){
//     e.preventDefault();
//     $("#spinner").html("<i class='fa fa-refresh fa-spin'></i> &nbsp;");
//     var form = $(this);
//     var url = form.attr('action');

//     $.ajax({
//         type: "POST",
//         dataType: "json",
//         url: url,
//         data: form.serialize(),
//         success: function(data){
//             $("#spinner").html("&nbsp;");
//             // show the success message from the server ...
//             let message = form.data("message") ?? data.message;
//             ShowSuccessModal(message, null);
//             $('.review_button').hide();
//         },
//         error: function(data) {
//             $("#spinner").html("&nbsp;");
//             if (data.message){
//                 ShowFailureToaster(data.message);
//             }
//             // console.log(data);
//         }
//     });
// });
