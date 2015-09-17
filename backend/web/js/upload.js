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
            PostInit: function() {
                document.getElementById('uploadfiles').onclick = function() {
                    uploader.start();
                    return false;
                };
            },


            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },
            FileUploaded:function (up, file, result) {
                $('#'+contatinerId+' p').text('code: '+result.code+' ; message: '+result.message);
            },
            Error: function(up, err) {
                document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });

    uploader.init();
}