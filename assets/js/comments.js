
$(document).on('ready', function() {

if (typeof id_refer !== 'undefined' && enable == true) {

  var lang_comments = [];

  lang_comments["english"] = {

    textareaPlaceholderText: 'Leave a comment',
    newestText: 'New',
    oldestText: 'Old',
    popularText: 'Most popular',
    attachmentsText: 'Show attachments',
    sendText: 'Comment',
    replyText: 'Answer',
    editText: 'Modify',
    editedText: 'Modified',
    youText: 'Me',
    saveText: 'Update',
    deleteText: 'Remove',
    hideRepliesText: 'Hide',
    viewAllRepliesText: 'Show all replies (__replyCount__)',
    noCommentsText: 'There are no comments'

  };

  lang_comments["portuguese_br"] = {

         textareaPlaceholderText: 'Deixar um comentário',
         newestText: 'Mais recentes',
         oldestText: 'Mais antigos',
         popularText: 'Mais popular',
         attachmentsText: 'Mostrar anexos',
         sendText: 'Comentário',
         replyText: 'Responder',
         editText: 'Modificar',
         editedText: 'Modificado',
         youText: 'Eu',
         saveText: 'Atualizar',
         deleteText: 'Remover',
         hideRepliesText: 'Ocultar',
         viewAllRepliesText: 'Mostrar todas as respostas (__replyCount__)',
         noCommentsText: 'Não há comentários'

  };

    lang_comments["spanish_am"] = {

     textareaPlaceholderText: 'Deja un comentario',
     newestText: 'Nuevo',
     oldestText: 'Viejo',
     popularText: 'Más popular',
     attachmentsText: 'Mostrar archivos adjuntos',
     sendText: 'Comentario',
     replyText: 'Answer',
     editText: 'Modificar' ,
     editedText: 'Modificado',
     youText: 'Yo',
     saveText: 'Actualizar',
     deleteText: 'Eliminar',
     hideRepliesText: 'Ocultar',
     viewAllRepliesText: 'Mostrar todas las respuestas (__replyCount__)',
     noCommentsText: 'No hay comentarios'

   },

  $('#comments-container').comments({
    textareaPlaceholderText: lang_comments[lang_user].textareaPlaceholderText,
    newestText: lang_comments[lang_user].newestText,
    oldestText: lang_comments[lang_user].oldestText,
    popularText: lang_comments[lang_user].popularText,
    attachmentsText: lang_comments[lang_user].attachmentsText,
    sendText: lang_comments[lang_user].sendText,
    replyText: lang_comments[lang_user].replyText,
    editText: lang_comments[lang_user].editText,
    editedText: lang_comments[lang_user].editedText,
    youText: lang_comments[lang_user].youText,
    saveText: lang_comments[lang_user].saveText,
    deleteText: lang_comments[lang_user].deleteText,
    hideRepliesText: lang_comments[lang_user].hideRepliesText,
    viewAllRepliesText: lang_comments[lang_user].viewAllRepliesText,
    noCommentsText: lang_comments[lang_user].noCommentsText,
    profilePictureURL: my_avatar,
    currentUserId: uid,
    roundProfilePictures: true,
    textareaRows: 2,
    enableAttachments: enable_attach,
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
