<?php               
include("conf/settings.php");
include("i18n.php");
include("helpers.php");
include("render.php");

// Connect to DB
$db_config = get_db_config();
//echo "dbconfig : " . $db_config['host'] . " - " . $db_config['user'] . " - " . $db_config['pass'] . " - " . $db_config['db']
mysql_connect($db_config['host'], $db_config['user'], $db_config['pass']);
@mysql_select_db($db_config['db']) or die( "Unable to select database");
mysql_set_charset('utf8');


// find the vid of the Tags vocabulary
$tags_vid = get_tags_vid();
//echo "tags vid : " . $tags_vid;

// find current language
$language = $_GET['lang'];
if (!$language){
    $language = get_default_language();
}

?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language;?>">
<head>
    <title><?php echo get_string('title', $language);?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="The Perfect 3 Column Liquid Layout: No CSS hacks. SEO friendly. iPhone compatible." />
	<meta name="keywords" content="The Perfect 3 Column Liquid Layout: No CSS hacks. SEO friendly. iPhone compatible." />
	<meta name="robots" content="index, follow" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="screen.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="tagger.css" media="screen" />
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
        <script type="text/javascript" src="tagger.js"></script>
        <script type="text/javascript" src="openclose.js"></script>
        <script type="text/javascript" src="tagsearch.js"></script>
        <script type="text/javascript">
$(document).ready(function(){
  var selectedId = 0;
  
  $(".article").click(function(){
    $(".article").each(function(index, element){
      $(element).removeClass("yellow_border");
    });
    $(this).addClass("yellow_border");
    console.log($(this).attr('id'));
  });
  
  $(".article").hover(function(){
    $(this).addClass("highlight");
  }, function(){
    $(this).removeClass("highlight");
  });
  
  $(".tag").hover(function(){
    $(this).addClass("highlight");
  }, function(){
    $(this).removeClass("highlight");
  });
  
  $(".tag").click(function(){
    // only actions if there is a checkbox
    if (this.childNodes[0].type == "checkbox"){
      // if unselected, make it selected
      if (!$(this).hasClass("yellow_border")){
        $(this).addClass("yellow_border");
        this.childNodes[0].checked = true;
      }else{
          $(this).removeClass("yellow_border");
          this.childNodes[0].checked = false;
      }
    }
  });
  
  fold_document()

});
</script>
</head>
<body>

<div id="header">
    <?php render_header($language);?>
</div>
<div class="colmask threecol">
	<div class="colmid">
		<div class="colleft">
			<div class="col1">
				<!-- Column 1 (middle) start -->
                               <?php
// if a constitution is selected : 
if (array_key_exists('id', $_GET)) {
  $book_id = $_GET['id'];
  if (array_key_exists('nid', $_GET)) {
      $selected_nid = $_GET['nid'];
  }
  render_constitution($book_id, $tags_vid, $language, $selected_nid);
  
} else {
  echo get_string("no_constitution_selected", $language);
}
?>
				<!-- Column 1 (middle) end -->
			</div>
			<div class="col2">
				<!-- Column 2 (left) start -->
<h3><?php echo get_string('select_constitution', $language);?></h3>
<?php render_constitution_list($language);?>

				<!-- Column 2 (left) end -->
			</div>
			<div class="col3">
				<!-- Column 3 (right) start -->
<?php
    // find terms for highlighted article
    $node_terms = array();
    $nid = "";
    if (array_key_exists('nid', $_GET)) {
        $nid = $_GET['nid'];
        $node_terms = get_node_terms($nid, $tags_vid);   
    }
    render_tag_column_header($nid, $language, $book_id); 
   ?>
<div class="content">
<?php render_tag_list($language, $tags_vid, $nid, $node_terms); ?> 

    </div> <!-- end content -->
				<!-- Column 3 (right) end -->
			</div>
		</div>
	</div>
</div>
<div id="footer">
</div>

</body>
</html>
