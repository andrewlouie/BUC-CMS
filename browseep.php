<!doctype html>
<html>
<head>
<title>Bridgeport United Church Event Pictures</title>
    <style>
.pic {
width: 150px;
height: 150px;
float: left;
position: relative;
text-align:center;
margin-bottom: 10px;
margin-left: 5px;
margin-right: 5px;
margin-top: 5px;
border: 2px transparent solid;
}
.pic img {
max-width: 100%;
max-height: 120px;
}
.pic:hover {
border: 2px red dotted;
}
.caption {
	font-size: 12px;
	text-align: center;
	position: absolute;
	bottom: 0px;
	left: 0px;
	width: 100%;
}
* {
	font-family: Constantia, "Lucida Bright", "DejaVu Serif", Georgia, serif;	
}
	</style>
	<script>
function uP(file) {

	opener.document.getElementById("imageURL").value = file;

	window.opener.changepic(file);
	window.close();
}

  </script>  </head>

<body>
<?php include ('upload.php'); ?>
<h1>Select A Picture</h1>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
    Select image to upload:<br>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form><br><br>
<?php
// Create recursive dir iterator which skips dot folders
$dir = new RecursiveDirectoryIterator('./Events',
    FilesystemIterator::SKIP_DOTS);

// Flatten the recursive iterator, folders come before their files
$it  = new RecursiveIteratorIterator($dir,
    RecursiveIteratorIterator::SELF_FIRST);

// Maximum depth is 1 level deeper than the base folder
$it->setMaxDepth(1);

// Basic loop displaying different messages based on file or folder
foreach ($it as $fileinfo) {
     if (($fileinfo->isFile())  && ((strtolower(substr($fileinfo->getFilename(),-3)) == "gif") || (strtolower(substr($fileinfo->getFilename(),-3)) == "jpg") || (strtolower(substr($fileinfo->getFilename(),-3)) == "png") || (strtolower(substr($fileinfo->getFilename(),-4)) == "jpeg"))) {
	printf("<div class='pic' onclick='javascript:uP(" . '"' . $fileinfo->getFilename() . '"' . ")'>");
	printf("<img src='Events/" . $fileinfo->getFilename() . "'><span class='caption'>");
        printf($fileinfo->getFilename());
	printf("</span></div>");
    }
}


?>
</body>
</html>
