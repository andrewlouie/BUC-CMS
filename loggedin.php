<?php 
	$auth = $_COOKIE['authorization'];
	header ("Cache-Control:no-cache");
	if(!$auth == "ok") {
		header ("Location:login.php");
		exit();
	}
?>
<html>
<head>
<title>Bridgeport United Church Login Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="ckeditor/ckeditor.js"></script>
	<link rel="stylesheet" href="ckeditor/samples/sample.css">
    <style>
	label {
	display: inline-block;
	width: 170px;
	height: 25px;
	text-align: right;	
	padding-right: 15px;
	font-weight: bold;
	}
#coverpic {
float:right;
margin-top: -90px;
margin-right: 300px;
width: 100px;
height: 100px;
text-align: center;
border: solid 1px gray;
}
#coverpic img {
max-width: 100px;
max-height: 100px;
height: auto;
border:none;
}

	</style>
<script>
var responseArray = new Array();
var timeoutID;
curtitle = "";
curimage = "";
curdate = "";
var newitemno = 0;
var redoitem = 0;
var redo = false;
var selectedvalue = 0;
var changing = false;
function adelete() {
	if (confirm("Are you sure?")) {
	CKEDITOR.instances.editor1.resetDirty()
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		loadOptions();
		changeItem(0);
		document.getElementById("message").innerHTML = "Item deleted";
}		}
	xmlhttp.open("GET", "deletebtn.php?z=x84bohs5&q=" + document.getElementById("eventlist").value, true);
	xmlhttp.send();
} }
function areset() {
document.getElementById("eventlist").options[0].selected = true;
changeItem(0);
}
function loadOptions() {
	var selectBox = document.getElementById("eventlist");
	selectBox.innerHTML = "";
	var opt = document.createElement('option');
	opt.value = 0;
	opt.text = "<< NEW >>";
	selectBox.options.add(opt);
	opt.selected = true;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var responseArray = xmlhttp.responseText.split("||");
			var selectBox = document.getElementById("eventlist");
			for (a=0;a < responseArray.length -1; a+=2) {
				var opt = document.createElement('option');
				opt.value = responseArray[a];
				opt.text = responseArray[a+1];
				selectBox.options.add(opt);
				if (responseArray[a] == newitemno) { opt.selected = true; selectedvalue = newitemno; }
			}
			newitemno = 0;
		}
	}
	xmlhttp.open("GET", "getlist.php", true);
	xmlhttp.send();
	
}
function acopy() {
	document.getElementById("deletebtn").disabled = true;
	document.getElementById("copybtn").disabled = true;
	document.getElementById("eventlist").options[0].selected = true;
	selectedvalue = 0;
}
function saveevent() {
	if (document.getElementById("title").value == "") { alert("Missing title"); return; }
	if (document.getElementById("toDate").value == "") { alert("Missing/invalid date"); return; }
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			if (selectedvalue == 0) newitemno = Number(xmlhttp.responseText);
			else newitemno = selectedvalue;
			document.getElementById("deletebtn").disabled = false;
			document.getElementById("copybtn").disabled = false;
			loadOptions();
			document.getElementById("message").innerHTML = "Saved";
			curtitle = document.getElementById("title").value;
			curdate = document.getElementById("toDate").value;
			curimage = document.getElementById("imageURL").value;
			CKEDITOR.instances.editor1.resetDirty();
			if (redo == true) changeItem(redoitem);
			if (xmlhttp.responseText.length > 5) alert("ERROR: " + xmlhttp.responseText);
		 }
	 }
	xmlhttp.open("POST", "updateevent.php", true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("z=x84bohs5&q=" + selectedvalue + "&a=" + document.getElementById("toDate").value + "&b=" + document.getElementById("title").value + "&c=" + document.getElementById("imageURL").value + "&d=" + encodeURIComponent(CKEDITOR.instances.editor1.getData().replace()));
}
function changeItem(newitem) {
window.clearTimeout(timeoutID);
timeoutID = window.setTimeout(function(test) { changing = false; }, 1000);
redo = false;
var newdate = document.getElementById("toDate").value
if (((document.getElementById("title").value != curtitle) || (document.getElementById("imageURL").value != curimage) || (newdate != curdate) || (CKEDITOR.instances.editor1.checkDirty())) && (changing == false)) {
	if (confirm("Save changes?")) {
		redo = true;
		redoitem = newitem;
		saveevent();
		return;
	}
}
changing = true;
selectedvalue = newitem;
newitemno = newitem;
if (newitem == 0) {
	document.getElementById("title").value = "";
	document.getElementById("toDate").value = "";
	document.getElementById("imageURL").value = "";
	CKEDITOR.instances.editor1.setData("");
	document.getElementById("coverpicpic").src = "placeholder.jpg";
	document.getElementById("deletebtn").disabled = true;
	document.getElementById("copybtn").disabled = true;
	curtitle = document.getElementById("title").value;
	curdate = document.getElementById("toDate").value;
	curimage = document.getElementById("imageURL").value;
	setTimeout(function(){ CKEDITOR.instances.editor1.resetDirty() },0);
}
if (newitem != 0) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var responseArray = xmlhttp.responseText.split("||");
			document.getElementById("title").value = responseArray[0];
			document.getElementById("toDate").value = responseArray[1];
			document.getElementById("imageURL").value = responseArray[2];
			CKEDITOR.instances.editor1.setData(decodeURIComponent(responseArray[3]));
			curtitle = document.getElementById("title").value;
			curdate = document.getElementById("toDate").value;
			curimage = document.getElementById("imageURL").value;
			if (responseArray[2].substring(0,4) == "http") document.getElementById("coverpicpic").src = responseArray[2];
			else document.getElementById("coverpicpic").src = "Events/" + responseArray[2];
			document.getElementById("deletebtn").disabled = false;
			document.getElementById("copybtn").disabled = false;
			setTimeout(function(){ CKEDITOR.instances.editor1.resetDirty() },0);
		}
	}
	xmlhttp.open("GET", "sqllookup.php?q=" + newitem, true);
	xmlhttp.send();
}
}
function changed() {
document.getElementById("message").innerHTML = "";
}
function changepic(address) {
if (address.substring(0,4) == "http") document.getElementById("coverpicpic").src = address;
else document.getElementById("coverpicpic").src = "Events/" + address;
}
</script>
    </head>

<body onload="loadOptions()">
<span style="font-size:24px;">Add/Update Events<a href="./" style="float:right">Home</a></span>
<form>
<label>Event:</label><select name="eventlist" id="eventlist" style="width:400px" onChange="javascript:changeItem(this.value)"><option value="0"><< NEW >></option>

</select> 

&nbsp;&nbsp;<input type="button" value="Delete" id="deletebtn" onclick="javascript:adelete()" disabled>
&nbsp;&nbsp; <input type="button" value="Copy" id="copybtn" onclick="javascript:acopy()" disabled>&nbsp;&nbsp; <input type="button" value="Reset" id="resetbtn" onclick="javascript:areset()"><br>
<label>Title:</label><input name="title" type="text" id="title" size="70" maxlength="100" onkeydown="changed()" onchange="changed()"><br>
<label>Date of event (the next day it will be moved to past events):</label><input name="toDate" type="date" id="toDate" onchange="changed()"><br><br>
<label>Image URL:</label><input name="imageURL" type="text" id="imageURL" maxlength="999" onkeydown="changed()" onchange="changepic(this.value)"> <input type="button" value="Browse" onclick="window.open('browseep.php','newWin','status=yes,width=900,height=600');">
<br><div id="coverpic"><img src="placeholder.jpg" id="coverpicpic"></div>
<label>Content:</label><br>
<textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10">
</textarea><br><input name="eventsubmit" type="button" id="eventsubmit" value="Save" onclick="javascript:saveevent()">
<span id="message" style="margin-left:25px;color:red;font-weight:bold"></span></form>
</body>
</html>
