$(function() {
  'use strict';

  $("#exampleDropzone1").dropzone({
    url: 'ajaxcall.php',
	addRemoveLinks:true,
	dictRemoveFile:'remove',
	maxFiles : 1
  });
});
