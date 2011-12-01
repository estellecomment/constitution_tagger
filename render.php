<?php

function render_tag($depth_diff, &$fold_numbers, $term_row, $node_terms, $nid){
      $current_depth = $term_row['depth'];
      
      // increment/reset the fold numbers
      $fold_number = "";
      $fold_class = "";
      $open_close_buttons= "■ ";
      if($depth_diff == 1){// note : we assume you can only open one level at a time
            $fold_numbers[$current_depth] += 1;   
            // reset all indexes above the one we just incremented
            for($i=$current_depth + 1; $i < count($fold_numbers); $i ++){
                $fold_numbers[$i] = 0;
            }
            $fold_number = get_fold_number($current_depth, $fold_numbers); // todo more complicated stuff
            $fold_class = ' head' . $fold_number;
            //$open_close_buttons = '<span id="show' . $fold_number . '" class="foldclosed" onclick="show_folder(\'' . $fold_number . '\')" style="visibility: hidden; position: absolute;">▶</span><span id="hide' . $fold_number . '" class="foldopened" onclick="hide_folder(\'' . $fold_number . '\')" style="visibility: visible; position: relative;">▼</span>';
      
            $open_close_buttons = '<span id="show' . $fold_number . '" class="foldclosed" onclick="show_folder(\'' . $fold_number . '\')" style="visibility: hidden; position: absolute;">▶</span>';//position: relative; visibility: hidden; ">+</span>';
            $open_close_buttons .= '<span id="hide' . $fold_number . '" class="foldopened" onclick="hide_folder(\'' . $fold_number . '\')" style="visibility: visible; position: relative;">▼</span>'; //position: absolute; ">-</span>';
            
      }
      if ($depth_diff < -1){
          for($i = 2; $i <= -$depth_diff; $i++){
              $fold_numbers[$current_depth + $i] = 0;
          }
      }
     
      // display the tag
      if (!array_key_exists('nid', $_GET)){
        echo '<div id="tag-' . $term_row['tid'] . '" class="tag depth-' . $term_row['depth'] . $fold_class . '">';
        echo $open_close_buttons;
        //echo '■ ';
      }else{
        if (in_array($term_row['tid'], $node_terms)){
            echo '<div id="tag-' . $term_row['tid'] . '" class="tag depth-' . $term_row['depth'] . $fold_class . ' yellow_border">';
            echo $open_close_buttons;
            echo '<input type="checkbox" name="' . $term_row['tid'] . '" value="' . $nid . '" checked/>';
        }else{
            echo '<div id="tag-' . $term_row['tid'] . '" class="tag depth-' . $term_row['depth'] . $fold_class . '">';
            echo $open_close_buttons;
            echo '<input type="checkbox" name="' . $term_row['tid'] . '" value="' . $nid . '"/>';         
        }
      }
      echo $term_row['name'] . '</div>';   
      
      // open/close divs
      if ($depth_diff < 0){ // note : you can close several levels at a time
          for($i = 1; $i <= -$depth_diff; $i++){
              echo '</div>';
          }
      }
      if ($depth_diff == 1){
          echo '<div id=fold' . $fold_number . ' class="fold">';
      }
}

function render_constitution($book_id, $tags_vid, $language, $selected_nid){  
  // display constitution name as title
  $q_str = "SELECT title FROM node WHERE nid = $book_id;";
  $result = mysql_query($q_str);
  $row = mysql_fetch_assoc($result);
  echo '<h3>' . $row['title'] . '</h3>';

  // display constitution
  echo '<div class="content">';
  $q_str = "SELECT node_revisions.nid, node.title, body from node_revisions, book, node WHERE node_revisions.nid = book.nid AND book.bid = $book_id AND node_revisions.nid = node.nid;";
  $result = mysql_query($q_str);
  $tag_list = array(); // for jsonizing
  while ($row = mysql_fetch_assoc($result)) { 
    echo "<a href='?lang=" . $language . "&id=$book_id&nid=" . $row['nid'] . "#" . $row['nid'] . "'>";
    
    $bordered = $selected_nid == $row['nid'] ? " yellow_border" : "";
    
    echo "<div id={$row['nid']} class='article" . $bordered . "'><h3>" . $row['title'] . "</h3>";
    echo $row['body'];
    
    // get the tags
    $q_str = "SELECT name, term_node.tid from term_node, term_data WHERE term_node.tid = term_data.tid AND term_node.nid = {$row['nid']} AND term_data.vid = $tags_vid;";
    $term_result = mysql_query($q_str);
    // display tags
    echo "<h4>Tags:</h4>";
    while ($term_row = mysql_fetch_assoc($term_result)) {
      echo ' ' . $term_row['name'] . '<br />';   
    }        
    
    // store tags to send them in json
    $tags = array();
    while ($term_row = mysql_fetch_assoc($term_result)) {
      $tags[] = $term_row['name']; 
    }                  
    $tag_list[$row['nid']] = $tags;
    
    echo "</div>";
    echo "</a>";
  }
  echo '</div>';// end content
  echo "<script type='text/javascript'>var tag_json = eval(".json_encode($tag_list).");console.log(tag_json);</script>";

}

function render_constitution_list($language){
    echo "<ul>";
    $q_str = "SELECT DISTINCT bid FROM book LEFT JOIN node ON book.nid = node.nid WHERE node.type = 'book' AND node.language = '$language';";
    $result = mysql_query($q_str);
    while ($row = mysql_fetch_assoc($result)) {
        $book_id = $row['bid'];
        $title_result = mysql_query("SELECT title FROM node WHERE nid = $book_id;");
        $title_row = mysql_fetch_assoc($title_result);
        echo "<li><a href='?lang=" . $language . "&id={$book_id}'>{$title_row['title']}</a></li>";
    }
    echo "</ul>";
}
function render_tag_column_header($nid, $language, $book_id){
    if ($nid != ""){
        echo '<div class="title"><h3>' . get_string('select_tags', $language) . '</h3></div>';
        
        // form to collect the checked tags.
        echo '<form action="save_tags.php" method="post">';
        // save button
        echo '<div class="save">';
        echo '<input type="submit" value="' . get_string('save_tags', $language) . '"/>';
        echo '</div>';
        // add an invisible field to get the current nid from
        echo '<input type="hidden" name="currentnid" value="' . $nid . '" />';
        echo '<input type="hidden" name="currentbookid" value="' . $book_id . '" />';
    }
    else{
        echo '<div class="title"><h3>Tags</h3></div>';        
    }
}

function render_tag_list($language, $tags_vid, $nid, $node_terms){
    
    echo '<div class="openclose-buttons">';
    // add close all / show all buttons
    echo '<a class="close-button" onclick="fold_document();">' . get_string('close_all', $language) . '</a>';
    echo '<a class="open-button" onclick="unfold_document();">' . get_string('open_all', $language) . '</a>';
    // add search button
    echo '<input type="button" value="' . get_string('search', $language) . '" onclick="searchPrompt(\'type words here\', true, \'green\', \'pink\');">';
    echo '</div>';
    
    // display terms
    echo '<div id="taglist">';
    
    $q_str = "SELECT term_lineage.depth, term_data.name, term_data.tid FROM term_lineage, term_data WHERE term_data.tid = term_lineage.tid AND term_data.vid=$tags_vid AND term_data.language='$language' ORDER BY term_lineage.lineage";
    $term_result = mysql_query($q_str);
    $current_depth = 0;
    $fold_numbers = array();
    $term_row = mysql_fetch_assoc($term_result);
    
    while ($next_term_row = mysql_fetch_assoc($term_result)) {
      $depth_diff = $next_term_row['depth'] - $term_row['depth'];
      render_tag($depth_diff, &$fold_numbers, $term_row, $node_terms, $nid);
      $term_row = $next_term_row;
    }
    // last row
    $depth_diff = 0 - $term_row['depth']; // go back to 0 depth.
    render_tag($depth_diff, &$fold_numbers, $term_row, $node_terms, $nid);
    
    echo "</div>"; // end taglist
}

function render_header($language){
    	// title
        echo "<h1>" . get_string('title', $language) . "</h1>";
        // subtitle instructions
        echo '<div class="instructions"><p>' . get_string('instructions', $language) . '</p></div>';
        // logout button
        echo '<div class="logout-button"><a href="./index.php?logout=1"><button style="font-size:20px;">Logout</button></a></div>';
	// tabs for language selection
        echo '<ul>';
	echo '<li><a href="?lang=en"'; if($language == 'en'){ echo 'class="active"';} echo '>English</a></li>';
	echo '<li><a href="?lang=fr"'; if($language == 'fr'){ echo 'class="active"';} echo '>Français</a></li>';
	echo '<li><a href="?lang=es"'; if($language == 'es'){ echo 'class="active"';} echo '>Español</a></li>';
	echo '<li><a href="?lang=ar"'; if($language == 'ar'){ echo 'class="active"';} echo '>العربية</a></li>';
        echo '</ul>';
}

?>
