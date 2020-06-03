$(document).on('ready', function() {

if($('#post_editor').length){

tinymce.init({
	  language: lang_user,
    menubar:false,
    statusbar: false,
    relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
    height : 350,
    entity_encoding : "raw",
    selector: 'textarea#post_editor',
    images_upload_base_path: base_url,
    images_upload_credentials: true,
    plugins: ["link image lists code media emoticons table autosave"],
    toolbar: 'undo redo restoredraft | link | code | bold italic underline | image | numlist bullist | table | media | media_user',
    setup: function (editor) {

      editor.ui.registry.addButton('media_user', {
        text: 'Media Gallery',
        onAction: function (_) {

          $('#modalGallery').data('folder','blog/posts_body');
          $('#modalGallery').modal('show');

           }

         });
      },images_upload_handler: function (blobInfo, success, failure) {

	       var xhr, formData;

	       xhr = new XMLHttpRequest();
	       xhr.withCredentials = false;

	       xhr.open('POST', base_url+'blog/content/upload_ck');
	       xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	       formData = new FormData();
	       formData.append('file', blobInfo.blob(), blobInfo.filename());

	       xhr.send(formData);

	       xhr.onload = function() {
	         var json;

	         if (xhr.status != 200) {
	           failure('HTTP Error: ' + xhr.status);
	           return;
	         }

	         json = JSON.parse(xhr.responseText);

	         if (json.status == 0) {

	                failure(json.message);
	           return;
	         }

	         success(json.location);
	       };
	   }

  });

  $(document).bind('media-user-selected', function(e,d){

    tinymce.activeEditor.insertContent('<img src="'+base_url+'uploads/'+d.location+'" height="'+d.height+'" width="'+d.width+'" />');

  });

	$('.check_co').change(function(){

				if($(this).attr('id') == 'Check_en_uploads' && $(this).val() == 1){
					$('#Check_en_comment').prop('checked',true);
				}
			
				if($(this).attr('id') == 'Check_en_comment' && $(this).is(":not(:checked)")){
					$('#Check_en_uploads').prop('checked',false);
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
