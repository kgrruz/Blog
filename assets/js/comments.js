
$(document).on('ready', function() {

if (typeof id_refer !== 'undefined' && enable == true) {

  $('#comments-container').comments({
    textareaPlaceholderText: lang_comments.textareaPlaceholderText,
    newestText: lang_comments.newestText,
    oldestText: lang_comments.oldestText,
    popularText: lang_comments.popularText,
    attachmentsText: lang_comments.attachmentsText,
    sendText: lang_comments.sendText,
    replyText: lang_comments.replyText,
    editText: lang_comments.editText,
    editedText: lang_comments.editedText,
    youText: lang_comments.youText,
    saveText: lang_comments.saveText,
    deleteText: lang_comments.deleteText,
    hideRepliesText: lang_comments.hideRepliesText,
    viewAllRepliesText: lang_comments.viewAllRepliesText,
    noCommentsText: lang_comments.noCommentsText,
    profilePictureURL: my_avatar,
    currentUserId: uid,
    roundProfilePictures: true,
    textareaRows: 2,
    enableAttachments: enable_attach,
    enableHashtags: false,
    enablePinging: false,
    enableUpvoting: false,
    postCommentOnEnter: true,
    readOnly:enablecomment,
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
        var comment  = JSON.parse(comment);
        if(comment.status){
          success(comment);
        }else{
          $.notify({ message: comment.message, type:'danger'});
        }
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

                var commentJSON = JSON.parse(commentJSON);

                    if(commentJSON.status){

                        successfulUploads.push(commentJSON);
                         serverResponded();

                       }else{

                         alert(commentJSON.message);

                        }

                          serverResponded();
                 },
                 error: function(data) {
                     error();
                 },
             });
         });
     }
  });

}

  });
