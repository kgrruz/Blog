
$(document).on('ready', function() {

if (typeof id_refer !== 'undefined') {


  $('#comments-container').comments({
    profilePictureURL: my_avatar,
    currentUserId: uid,
    roundProfilePictures: true,
    textareaRows: 2,
    enableAttachments: true,
    enableHashtags: false,
    enablePinging: false,
    enableUpvoting: false,
    postCommentOnEnter: true,
    enableEditing: true,
    enableDeleting: true,
    enableReplying: true,
    getComments: function(success, error) {
      $.ajax({
          type: 'get',
          url: base_url+'blog/comments/getComments/'+id_refer,
          success: function(commentsArray) {
            success(JSON.parse(commentsArray));

          },
          error: error
      });
    },
    postComment: function(commentJSON, success, error) {

      commentJSON[csrf_name] = csrf;
      commentJSON.qp = id_refer;
      commentJSON.created_by = author;
      commentJSON.action = 'insert';

  $.ajax({
      type: 'post',
      url: base_url+'blog/comments/postComments',
      data: commentJSON,
      success: function(comment) {
          success(JSON.parse(comment))
      },
      error: error
  });
  },
    putComment: function(commentJSON, success, error) {


      commentJSON[csrf_name] = csrf;
      commentJSON.qp = id_refer;
      commentJSON.created_by = author;
      commentJSON.action = 'edit';

  $.ajax({
      type: 'post',
      url: base_url+'blog/comments/postComments',
      data: commentJSON,
      success: function(comment) {
          success(JSON.parse(comment))
      },
      error: error
  });


    },
    deleteComment: function(commentJSON, success, error) {


      commentJSON[csrf_name] = csrf;
      commentJSON.qp = id_refer;
      commentJSON.created_by = author;

  $.ajax({
      type: 'post',
      url: base_url+'blog/comments/deleteComments',
      data: commentJSON,
      success: function(comment) {
          success(JSON.parse(comment))
      },
      error: error
  });


    },
    uploadAttachments: function(commentArray, success, error) {
         var responses = 0;
         var successfulUploads = [];

         var serverResponded = function() {
             responses++;

             // Check if all requests have finished
             if(responses == commentArray.length) {

                 // Case: all failed
                 if(successfulUploads.length == 0) {
                     error();


                 // Case: some succeeded
                 } else {
                     success(successfulUploads);

                 }
             }
         }



         $(commentArray).each(function(index, commentJSON) {

             // Create form data
             var formData = new FormData();
             $(Object.keys(commentJSON)).each(function(index, key) {
                 var value = commentJSON[key];
                 if(value) formData.append(key, value); formData.append(csrf_name, csrf); formData.append('qp', id_refer);
             });

             $.ajax({
                 url: base_url+'blog/comments/uploadAttachments/',
                 type: 'POST',
                 data: formData,
                 cache: false,
                 contentType: false,
                 processData: false,
                 success: function(commentJSON) {
                    console.log(commentJSON);
                var commentJSON = JSON.parse(commentJSON);
                    if(commentJSON.status){

                        successfulUploads.push(commentJSON);
                         serverResponded();

                       }else{

                          message_alert_ajax(commentJSON.message,'alert-error');

                         }

                          serverResponded();
                 },
                 error: function(data) {
                     serverResponded();
                 },
             });
         });
     }
  });

}

  });
