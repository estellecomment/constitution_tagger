<?php
include("conf/settings.php");
include("helpers.php");
include("i18n.php");


// Connect to DB
$db_config = get_db_config();
//echo "dbconfig : " . $db_config['host'] . " - " . $db_config['user'] . " - " . $db_config['pass'] . " - " . $db_config['db']
mysql_connect($db_config['host'], $db_config['user'], $db_config['pass']);
@mysql_select_db($db_config['db']) or die( "Unable to select database");
mysql_set_charset('utf8');

// find the vid of the Tags vocabulary
$tags_vid = get_tags_vid();


// ACTIONS ON FORM DATA
$current_nid = $_POST['currentnid'];
// find the node translation id
$q_str = "SELECT * FROM node WHERE nid=$current_nid;";
$result = mysql_query($q_str);
$current_noderow = mysql_fetch_assoc($result);
$tnid = $current_noderow['tnid'];
$language = $current_noderow['language'];

// find the node translations
if ($tnid > 0){
        $q_str = "SELECT * FROM node WHERE tnid=$tnid";
        $result_tr_nodes = mysql_query($q_str);
}else{ // if the $tnid is 0, then we just keep the original noderow.
        $q_str = "SELECT * FROM node WHERE nid=$current_nid";
        $result_tr_nodes = mysql_query($q_str);
}
$noderows = get_all_records($result_tr_nodes, 'language');



// ************************************************
// set the term_node relationships for the checked boxes
// *********************************************

foreach($_POST as $tid => $nid){
    if ($tid == 'currentnid' || $tid == 'currentbookid'){
        // info passed along with the form, shouldn't be treated as textboxes
        continue;
    }
    $checked_boxes[] = $tid;
    
    // find the term translations
    $q_str = "SELECT * from term_data WHERE tid=$tid;";
    $result = mysql_query($q_str);
    $termrow = mysql_fetch_assoc($result);
    $trid = $termrow['trid'];
    if ($trid > 0){
        $q_str = "SELECT * FROM term_data WHERE trid=$trid";
        $result = mysql_query($q_str);
        $termrows = get_all_records($result, 'language');
    }else{ // if the trid is 0, then just keep the original termrow.
        $termrows = array($termrow['language'] => $termrow);
    }
    
    // update term_node records for all languages
    foreach($noderows as $noderow){
        if($termrows[$noderow['language']]){ // test if term exists in node language
            $tid_to_set = $termrows[$noderow['language']]['tid'];
            $nid_to_set = $noderow['nid'];
            // check if the record exists already
            $q_str = "SELECT * FROM term_node WHERE vid=$nid_to_set AND tid=$tid_to_set";
            if(mysql_num_rows(mysql_query($q_str)) == 0){
                // insert the record if it doesn't exist
                $q_str = "INSERT INTO term_node VALUES ($nid_to_set, $nid_to_set, $tid_to_set);";
                if(!mysql_query($q_str)){
                    echo 'ERROR adding term "' . $termrows[$noderow['language']]['name'] . '"(' . $tid_to_set . ') to node "' . $noderow['title'] . '"(' . $nid_to_set . '). <br />';
                }else{
                    echo 'Added term "' . $termrows[$noderow['language']]['name'] . '"(' . $tid_to_set . ') to node "' . $noderow['title'] . '"(' . $nid_to_set . ').<br />';
                }
                //echo 'Pretented to add term "' . $termrows[$noderow['language']]['name'] . '"(' . $tid_to_set . ') to node "' . $noderow['title'] . '"(' . $nid_to_set . ').<br />';
            }
        }
    }
}

//*********************************************
// unset the term_node relationships for the not-clicked boxes
//*********************************************

// find the unclicked terms
$q_str = "SELECT * FROM term_data WHERE language='{$current_noderow['language']}' AND vid=$tags_vid";
foreach($checked_boxes as $checked_tid){// todo if no checked boxes?
    $q_str .= " AND tid<>$checked_tid";
}
$result = mysql_query($q_str);
$termrows = get_all_records($result);

// loop through the terms, checking if they are assigned to the current node
foreach($termrows as $termrow){
    // if the term is assigned to the current node
    $q_str = "SELECT * FROM term_node WHERE vid=$current_nid AND tid={$termrow['tid']}";
    if(mysql_num_rows(mysql_query($q_str)) > 0){
        // find translations for this term
        $trid = $termrow['trid'];
        if ($trid > 0){
            $q_str = "SELECT * FROM term_data WHERE trid=$trid";
            $result = mysql_query($q_str);
            $term_trs = get_all_records($result, 'language');
        }else{ // if the trid is 0, then just keep the original termrow.
            $term_trs = array($termrow['language'] => $termrow);
        }
        // delete records accross all languages
        foreach($noderows as $nodelanguage => $noderow){
            // find if the tag is present
            $nid_to_delete = $noderow['vid'];
            $tid_to_delete = $term_trs[$nodelanguage]['tid'];
            //if the term has a translation in the node language
            if ($tid_to_delete){
                // test if the term_node record exists
                $q_str = "SELECT * FROM term_node WHERE vid=$nid_to_delete AND tid=$tid_to_delete";
                if(mysql_num_rows(mysql_query($q_str)) > 0){
                    $q_str = "DELETE FROM term_node WHERE vid=$nid_to_delete AND tid=$tid_to_delete";
                    mysql_query($q_str);
                    echo 'Deleted term "' . $term_trs[$nodelanguage]['name'] . '"(' . $tid_to_delete . ') from node "' . $noderow['title'] . '"(' . $nid_to_delete . ').<br />';                
                }
            }
        }
    }

}


// back button
$current_book_id = $_POST['currentbookid'];
echo '<a href="index.php?lang=' . $language . '&id=' . $current_book_id . '&nid=' . $current_nid . '#' . $current_nid . '"><button type="button">Go back to tagging</button></a>';

?>
