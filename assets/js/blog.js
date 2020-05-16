$(document).on('ready', function() {

if($('#post_editor').length){

  tinymce.init({
    menubar:false,
    statusbar: false,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    height : 350,
    entity_encoding : "raw",
    selector: 'textarea#post_editor',
    images_upload_url: base_url+'admin/content/blog/upload_ck',
    images_upload_base_path: base_url,
    images_upload_credentials: true,
    plugins: ["link image lists code media emoticons table"],
    toolbar: 'undo redo | link | code | bold italic underline | image | numlist bullist | table | media'

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
