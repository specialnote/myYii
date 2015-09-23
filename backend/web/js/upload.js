function initCoverImageUploader(buttonId,contatinerId,maxFileSize,url,csrfToken){

    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button :buttonId, // you can pass an id...
        container: contatinerId, // ... or DOM Element itself
        url : url,
        flash_swf_url : '@vendor/moxiecode/plupload/js/Moxie.swf',
        silverlight_xap_url : '@vendor/moxiecode/plupload//js/Moxie.xap',

        filters : {
            max_file_size : maxFileSize,
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png"},
                {title : "Zip files", extensions : "zip"}
            ]
        },
        multipart_params:{
            '_csrf':csrfToken
        },
        init: {
            FilesAdded: function(up, files) {
                uploader.start();
            },
            UploadProgress: function(up, file) {
                $('#'+contatinerId+' p').text('已上传:'+file.percent+'%');
            },
            FileUploaded:function (up, file, result) {
                result =  $.parseJSON(result.response);
                if(result.code == 0){
                    $('#'+buttonId).html('<img src="'+result.path+'" height="50" />');
                    $('#hidden_input').val(result.path);
                }
            },
            Error: function(up, err) {
                document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });

    uploader.init();
}