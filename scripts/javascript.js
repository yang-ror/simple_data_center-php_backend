// =============================================================================
//  javascript.js
//  project: Simple Data Center
//  author: Zifan Yang
//  date created: 2021-03-18
// =============================================================================
function deleteFile(filename){
    var fname = filename.replaceAll('+', '%2B');
    fname = fname.replaceAll('?', '%26');
    window.location.replace("./php/deleteFile.php?fname=" + fname);
}

//delete link by the id
function deleteLink(id){
    window.location.replace("./php/delete.php?from=links&id=" + id);
}

//delete note by the id
function deleteNote(id){
    window.location.replace("./php/delete.php?from=notes&id=" + id);
}

//select the text in a card and copy to clipboard
function selectAllAndCopy(element){
    var doc = document, text = doc.getElementById(element), range, selection;    
    if(doc.body.createTextRange){
        range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    }
    else if(window.getSelection){
        selection = window.getSelection();
        range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }

    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(element));
        range.select().createTextRange();
        document.execCommand("copy");
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(element));
        window.getSelection().addRange(range);
        document.execCommand("copy");
    }
}

function resizeElement(){
    var imagesCardTitles = document.getElementsByClassName("image-card-title");
    if(imagesCardTitles.length > 0){
        var widthOfTitleBar = imagesCardTitles[0].clientWidth;
        var widthOfTitle = widthOfTitleBar - 60;
        var widthOfTitleStr = widthOfTitle.toString() + "px";
        var imagesTitleHolders = document.getElementsByClassName("image-title-holder");
        for(var i = 0; i < imagesTitleHolders.length; i++){
            imagesTitleHolders[i].style.width = widthOfTitleStr;
        }
    }

    var files = document.getElementsByClassName("files");
    if(files.length > 0){
        var widthOfFileTitleHolder = files[0].clientWidth;
        var widthOfFileTitle = widthOfFileTitleHolder - 80;
        var widthOfFileTitleStr = widthOfFileTitle.toString() + "px";
        var fileNameHolder = document.getElementsByClassName("file-name-holder");
        for(var i = 0; i < fileNameHolder.length; i++){
            fileNameHolder[i].style.width = widthOfFileTitleStr;
        }
    }

    var links = document.getElementsByClassName("links");
    if(links.length > 0){
        var widthOfLinkTitleHolder = links[0].clientWidth;
        var widthOfLinkTitle = widthOfLinkTitleHolder - 80;
        var widthOfLinkTitleStr = widthOfLinkTitle.toString() + "px";
        var linkHolders = document.getElementsByClassName("link-holder");
        for(var i = 0; i < linkHolders.length; i++){
            linkHolders[i].style.width = widthOfLinkTitleStr;
        }
    }
}

$( document ).ready(function() {
    resizeElement();
    
    $('#progress_div').hide();
    
    $('#uploadForm').submit(function(e) {	
        if($('#nfile').val()) {
            e.preventDefault();
            $('#progress_div').show();
            $(this).ajaxSubmit({
                beforeSubmit: function() {
                    $("#upload-progress-bar").width("0%");
                },
                uploadProgress: function (event, position, total, percentComplete){
                    $("#upload-progress-bar").width(percentComplete + '%');
                },
                success:function (){
                    // $('#progress_div').hide();
                    console.log("refresh");
                    location.reload();
                },
                resetForm: true 
            }); 
            return false; 
        }
    });
});