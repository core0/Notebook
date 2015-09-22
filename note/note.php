<?php
/*$path_to_template = "default.xml";

if (isset($_GET["login"]))
{
	$login = $_GET["login"];
	setcookie("log",$login,time()+3600*24*365,"/",$_SERVER['SERVER_NAME']);
}
else 
{
	header("Location: /404.html");
}*/

header("Content-Type: text/html; charset=utf-8");
$doc = new DOMDocument();
$doc->load("profiles/note.xml");

require 'password.php';
?>
<html>
<head>
<title>
dem0n.ws - notes
</title>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-13156915-4']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script type="text/javascript" src="jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="tiny_mce/tiny_mce.js" ></script>
<link href="style.css" rel="stylesheet" media="all" />

<script type="text/javascript">


    //редактор
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
	plugins : "emotions,inlinepopups,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist",

	// Theme options
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,code,|,forecolor,backcolor",
	theme_advanced_buttons3 : "hr,removeformat,|,sub,sup,|,charmap,emotions,|,print,|,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Style formats
	style_formats : [
		{title : 'Bold text', inline : 'b'},
		{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
		{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}}
	]
});


$(document).ready( function(){ 
	/*setTimeout(function(){$(".header_of_docs li:first").click()}, 1000);*/
    
    document.current_doc!=null;
    //добавить запись
	$(".add_note").live('click', function (){
			add_note();
		});
	//получение контента
	$(".note_name").live('click', function(){
			get_content(this);
		});
    //удаление записи
    $(".rm_note").live('click', function (){
			rm_note(this);
		});


});
</script>

</head>
<body>
<div class="header_of_docs" >
<p>dem0n.ws - notes</p>
<ul>



<?php
//извлечение записей
$mynotes = $doc->getElementsByTagName("note");

foreach($mynotes as $nt_name){
	echo "<li class='note_name' >",$nt_name->attributes->getNamedItem("name")->nodeValue,"</li>";
}

?>
</ul>
<img src='img/add.png' class='add_note'>
<img src='img/rm.png' class='rm_note'>    
</div>


<div class="content">

<textarea class="txt" name="content"></textarea>

<img alt="save me" src="img/Ok.png" class="ok">
</div>

<script type="text/javascript">


//передаётся запись на которую нажали и запросили его содержимое
function get_content(obj)
{
    //убираев выделение у предыдущей записи и выделяем текущую запись
    $(document.current_doc).css({"fontWeight":"100"});
	document.current_doc=$(obj);    
    $(obj).css({"fontWeight":"bold"});

	save_effect(1);

    //запрос содержимого
	$.get(
            "view.php",
            {"name":$(obj).text()},
			function (data){
		//$(".txt").val(data);

		save_effect(0);
		tinyMCE.activeEditor.setContent(unescape(data));
	});
		
}
//создание новой записи
function add_note(){   

    if($(".note_name").length > 0)
    {
        //парсинг числа - будет использовано для создания новой записи
        var new_name = $(".note_name:last").text();
        var numb = parseInt(new_name.match(/\d{1,}/))+1;
        var new_name = new_name.replace(/\d{1,}/,numb);
    }
    else
    {
        var new_name = "note1";
    }

	$(".add_note").hide();
	$.get("addnote.php",
			{name: new_name},
			function (data) {				
				ins_li(new_name);
			});
}

//удаление записи
function rm_note(){

    var name_note_for_delete = $(document.current_doc).text();
    
	$.get("rmnote.php",
			{name: name_note_for_delete},
			function (data) {
				if(data != "not found")
                {
                    //
                }
			});

     $(document.current_doc).remove();
     document.current_doc=null;
}
// добавление новой записи в хтмл
function ins_li(new_name_li){

	var new_li = document.createElement("li");
	$(new_li).addClass("note_name").text(new_name_li);
	$("ul").append(new_li);
	$(".add_note").show();
}

//сохранение
$("img.ok").click(function (){

    if(document.current_doc!=null)
    {
        var tinymce_content = escape(tinyMCE.activeEditor.getContent()); //$(".txt").val();
        save_effect(1);
        $.post(
                "save.php",
                {name:$(document.current_doc).text(), value:tinymce_content},
                function (data) {
                    save_effect(0);
                });
    }
    else
    {
        alert("not select record");
    }
	
});

//эффект
function save_effect(state){
	if(state==1)
	{
		$("body").append($("<div>").addClass("shadow"));
		$(".shadow").append($("<img />").attr({src:"img/loading2.gif",alt:"loading",class:"save_img"}));
	}
	else{
		$(".shadow").hide();
	}
}






</script>
</body>
</html>

