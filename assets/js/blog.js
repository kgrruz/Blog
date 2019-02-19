$(document).on('ready', function() {

if($('#post_editor').length){

  var lang = 'pt-br';

  switch(lang_user) {
    case 'portuguese_br':
        lang = 'pt-br';
        break;
    case 'english':
        lang = 'en-ca';
        break;
        case 'spanish_am':
            lang = 'es';
}

  var editor =  CKEDITOR.replace( 'post_editor',{
   extraPlugins : 'uploadimage,popup,filetools,filebrowser,wordcount,notification,youtube',
   uploadUrl : base_url+'admin/content/blog/upload_ck',
   filebrowserUploadUrl: base_url+'blog/upload_ck',
   language: lang,
   //startupFocus : true,
  // startupFocus : 'end',
   youtube_responsive: false,
   youtube_related: false,
   youtube_controls: false,
   youtube_disabled_fields : ['txtEmbed', 'chkAutoplay','chkResponsive','chkRelated','chkControls'],
   allowedContent : true,

   toolbar:[
           { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
           { name: 'clipboard', items: [ 'Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
           { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Strike', '-', 'TextColor' ] },
           { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blocks' ] },
           { name: 'links', items: [ 'Link', 'Unlink' ] },
           { name: 'insert', items: [ 'Image', 'Table', 'SpecialChar','Youtube' ] },
           { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Scayt' ] }
       ],
       height:400,
       resize_enabled:true,
       wordcount: {
           showParagraphs: false,
           showWordCount: true,
           showCharCount: true,
           countSpacesAsChars: false,
           countHTML: false,
           maxWordCount: -1,
           maxCharCount: 4000
         }

 });

}

 if($('.post_body').length){

$('.post_body a').each(function() {

   var a = new RegExp(base_url);
   if (!a.test($(this).attr('href'))){
      $(this).attr("target","_blank");
   }

 });


   $(".post_body img").addClass("img-fluid");


   }



});
