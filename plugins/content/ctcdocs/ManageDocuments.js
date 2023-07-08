function RemoveFile(p_root, p_subdir, p_filename) {
    if (confirm('Do you want to remove file ' + p_filename + ' from ' + p_subdir)) {
        var formdata = new FormData();
        formdata.append('action', 'RemoveFile');
        formdata.append('root', p_root);
        formdata.append('subdir', p_subdir);
        formdata.append('filename', p_filename);
        jQuery(function ($) {
            $.ajax({
                url: 'index.php?option=com_ajax&plugin=managectcdocuments&group=content&format=raw',
                type: 'POST',
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    result = JSON.parse(data);
                    if (result.success) {
                        //alert(result.message);
                        location.reload(true); // TODO change to dom if can be bothered
                    } else
                        alert(result.message);
                },
                error: function (data) {
                    alert("File removal failed");
                }
            });
        });
    }
}

function DoRenameDocumentFolder(p_root, p_subdir) {
    // code goes here
    var newname = document.getElementsByClassName("InputDialogText")[0].value;
    var dialog = document.getElementsByClassName("InputDialog")[0];
    try{
        dialog.close();
    }catch(err){
        // Ignore unimplemented dialog element
    }
    dialog.parentElement.removeChild(dialog);
    if (p_subdir !== newname) {
        var formdata = new FormData();
        formdata.append('action', 'RenameFolder');
        formdata.append('root', p_root);
        formdata.append('subdir', p_subdir);
        formdata.append('newname', newname);
        jQuery(function ($) {
            $.ajax({
                url: 'index.php?option=com_ajax&plugin=managectcdocuments&group=content&format=raw',
                type: 'POST',
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    result = JSON.parse(data);
                    if (result.success){
                        //alert(result.message);
                        location.reload(true); // TODO change to dom if can be bothered
                    }else
                        alert(result.message);
                },
                error: function (data) {
                    alert("Folder rename failed");
                }
            });
        });
    }
}

function CancelDialog(){
    var dialog = document.getElementsByClassName("InputDialog")[0];
    try{
        dialog.close();
    }catch(err){
        // Ignore unimplemented Dialog
    }
    dialog.parentElement.removeChild(dialog);
    //location.reload(true);
}

function ShowInputDialog(p_defaulttext, p_okEvent){
    dialog = document.createElement("DIALOG");
    namebutton = document.activeElement;
    namebutton.parentElement.appendChild(dialog);
    dialog.className = "InputDialog";
    inputtext = document.createElement("input");
    inputtext.className = "InputDialogText";
    okbutton = document.createElement("button");
    okbutton.className = "InputDialogOK";
    okbutton.textContent = "OK";
    cancelbutton = document.createElement("button");
    cancelbutton.className = "InputDialogCancel";
    cancelbutton.textContent = "Cancel";
    dialog.appendChild(inputtext);
    dialog.appendChild(okbutton);
    dialog.appendChild(cancelbutton);
    inputtext.value = p_defaulttext;
    okbutton.addEventListener('click', p_okEvent /*DoRenameDocumentFolder.bind(null, p_root, p_subdir)*/, false);
    cancelbutton.addEventListener('click', CancelDialog);
    try{
        dialog.showModal();
    }catch(err){
        // Ignore for browsers with no dialog implementation        
    }
}

function RenameDocumentFolder(p_root, p_subdir){
   ShowInputDialog(p_subdir, DoRenameDocumentFolder.bind(null, p_root, p_subdir));
}

function DoRenameFile(p_root, p_subdir, p_filename) {
    dialog = document.getElementsByClassName("InputDialog")[0];
    var newname = document.getElementsByClassName("InputDialogText")[0].value;
    try{
        dialog.close();
    }catch(err){
        // Ignore for browsers with no dialog implementation        
    }
    
    if (p_filename !== newname) {
        var formdata = new FormData();
        formdata.append('action', 'RenameFile');
        formdata.append('root', p_root);
        formdata.append('subdir', p_subdir);
        formdata.append('filename', p_filename);
        formdata.append('newname', newname);
        jQuery(function ($) {
            $.ajax({
                url: 'index.php?option=com_ajax&plugin=managectcdocuments&group=content&format=raw',
                type: 'POST',
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    result = JSON.parse(data);
                    if (result.success){
                        //alert(result.message);
                        location.reload(true); // TODO change to dom if can be bothered
                    }else
                        alert(result.message);
                 },
                error: function (data) {
                    alert("File rename failed");
                }
            });
        });
    }
}

function RenameFile(p_root, p_subdir, p_filename){
    ShowInputDialog(p_filename, DoRenameFile.bind(null, p_root, p_subdir, p_filename));
}

var choosefile;

function UploadCTCDocuments(p_root, p_subdir) {
    //choosefile doesn't need to be visible
    choosefile = document.createElement('input');
    choosefile.type = 'file';
    choosefile.class = 'inputFile';
    choosefile.title = "Choose new document";
    choosefile.style = "display:none";
    choosefile.addEventListener('change', DoUpload.bind(null, p_root, p_subdir), false);
    uploadbutton = document.activeElement;
    uploadbutton.parentElement.appendChild(choosefile);
    choosefile.click();
}

function DoUpload(p_root, p_subdir) {
    var files = choosefile.files;
    if (files.size === 0)
        return;
    var formdata = new FormData();
    formdata.append(files[0].name, files[0]);
    formdata.append('action', 'UploadFile');
    formdata.append('root', p_root);
    formdata.append('subdir', p_subdir);
    var progress = document.getElementsByClassName('progress' + p_subdir)[0];
    jQuery(function ($) {
        $.ajax({
            // Process upload via Joomla ajax component
            // Note that there doesn't need to actually be a plugin with that name
            // Just any plugin with onAjax... method in the specified group
            // In this case handled by the CTCDocs plugin
            url: 'index.php?option=com_ajax&plugin=managectcdocuments&group=content&format=raw',
            type: 'POST',
            // Form data
            data: formdata,
            // Tell jQuery not to process data or worry about content-type
            // You *must* include these options!
            cache: false,
            contentType: false,
            processData: false,
            // Custom XMLHttpRequest
            xhr: function () {
                var xhrCustom = $.ajaxSettings.xhr();
                if (xhrCustom.upload) {
                    // For handling the progress of the upload
                    xhrCustom.upload.addEventListener('progress',
                            function (e) {
                                if (e.lengthComputable) {
                                    progress.style.display = "inline-block";
                                    progress.value = e.loaded;
                                    progress.max = e.total;
                                }
                            }
                    , false);
                }
                return xhrCustom;
            },
            success: function (data) {
                progress.style.display = "none";
                result = JSON.parse(data);
                if (result.success) {
                    //alert(result.message);
                    location.reload(true); // TODO change to dom if can be bothered
                } else
                    alert(result.message);
            },
            error: function (data) {
                progress.style.display = "none";
                alert("Upload failed");
            }
        });
    });

}

function DoNewDocumentFolder(p_root) {
    dialog = document.getElementsByClassName("InputDialog")[0];
    var newname = document.getElementsByClassName("InputDialogText")[0].value;
    try{
        dialog.close();
    }catch(err){
        // Ignore for browsers with no dialog implementation        
    }
    var formdata = new FormData();
    formdata.append('action', 'NewFolder');
    formdata.append('root', p_root);
    formdata.append('newname', newname);
    jQuery(function ($) {
        $.ajax({
            url: 'index.php?option=com_ajax&plugin=managectcdocuments&group=content&format=raw',
            type: 'POST',
            data: formdata,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                result = JSON.parse(data);
                if (result.success) {
                    //alert(result.message);
                    location.reload(true); // TODO change to dom if can be bothered
                } else
                    alert(result.message);
            },
            error: function (data) {
                alert("New folder failed");
            }
        });
    });
}

function NewDocumentFolder(p_root){
    ShowInputDialog("", DoNewDocumentFolder.bind(null, p_root));
}
;


