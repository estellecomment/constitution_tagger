<?php
include("../conf/settings.php"); 

// connect to DB
$credentials = get_db_config();
mysql_connect($credentials['host'], $credentials['user'], $credentials['pass']);
@mysql_select_db($credentials['db']) or die( "Unable to select database");
mysql_set_charset('utf8');

// find the vid of the Tags vocabulary
$q_str = "SELECT vid FROM vocabulary WHERE name='Tags'";
$result = mysql_query($q_str);
$vidrow = mysql_fetch_assoc($result);
$tags_vid = $vidrow['vid'];
echo 'Tags vid : ' . $tags_vid . '</br></br>';

// get params
//$tags_to_set = get_tags_to_set();
$tags_to_set = array();
$tags_to_set['language_1'] = $_POST['language_1'];
$tags_to_set['language_2'] = $_POST['language_2'];
$tags_to_set['first_tid_1'] = $_POST['first_tid_1'];
$tags_to_set['first_tid_2'] = $_POST['first_tid_2'];
$tags_to_set['last_tid_1'] = $_POST['last_tid_1'];
$tags_to_set['last_tid_2'] = $_POST['last_tid_2'];
foreach ($tags_to_set as $key => $val){
    echo $key . ' : ' . $val . '</br>';
}
echo '</br>';


echo '<form method="post" action="build_translation_sets_terms_real.php">
    <input type="hidden" name="language_1" value="' . $tags_to_set['language_1'] . '" />
    <input type="hidden" name="language_2" value="' . $tags_to_set['language_2'] . '" />
    <input type="hidden" name="first_tid_1" value="' . $tags_to_set['first_tid_1'] . '" />
    <input type="hidden" name="first_tid_2" value="' . $tags_to_set['first_tid_2'] . '" />
    <input type="hidden" name="last_tid_1" value="' . $tags_to_set['last_tid_1'] . '" />
    <input type="hidden" name="last_tid_2" value="' . $tags_to_set['last_tid_2'] . '" />
    <input type="submit" name="Submit" value="Set these translations" />
    </form>';
echo '<form method="post" action="term_translation_form.php">
    <input type="hidden" name="language_1" value="' . $tags_to_set['language_1'] . '" />
    <input type="hidden" name="language_2" value="' . $tags_to_set['language_2'] . '" />
    <input type="hidden" name="first_tid_1" value="' . $tags_to_set['first_tid_1'] . '" />
    <input type="hidden" name="first_tid_2" value="' . $tags_to_set['first_tid_2'] . '" />
    <input type="hidden" name="last_tid_1" value="' . $tags_to_set['last_tid_1'] . '" />
    <input type="hidden" name="last_tid_2" value="' . $tags_to_set['last_tid_2'] . '" />
    <input type="submit" name="Back" value="Go back to selecting terms" />
    </form>';


// get the tags 
$q_str = "SELECT * from term_data WHERE language='{$tags_to_set['language_1']}' AND vid={$tags_vid} AND tid>={$tags_to_set['first_tid_1']} AND tid<={$tags_to_set['last_tid_1']} ORDER BY tid";
$result = mysql_query($q_str);
$tags_1 = array();
while ($row = mysql_fetch_assoc($result)) { 
    $tags_1[] = $row;
}
$q_str = "SELECT * from term_data WHERE language='{$tags_to_set['language_2']}' AND vid={$tags_vid} AND tid>={$tags_to_set['first_tid_2']} AND tid<={$tags_to_set['last_tid_2']} ORDER BY tid";
$result = mysql_query($q_str);
$tags_2 = array();
while ($row = mysql_fetch_assoc($result)) { 
    $tags_2[] = $row;
}

// count the tags and abort if the count is wrong
echo 'There are ' . count($tags_1) . ' terms in first language, ' . count($tags_2) . ' terms in second language.</br>';
if (count($tags_1) != count($tags_2)){
    exit('ERROR : different number of tags. Aborting.</br>');
}
if (count($tags_1) == 0 || count($tags_2) == 0){
    exit('ERROR : one of the tag lists is empty. Aborting.</br>');
}
$tag_count = count($tags_1);
echo '</br>';


// set the translation sets
for ($i=0; $i<$tag_count; $i++){
    $tag_1 = $tags_1[$i];
    $tag_2 = $tags_2[$i];
    echo ' - "' .$tag_1['name'] . '" and "' . $tag_2['name'] . '"</br>';
    echo 'Trids : ' . $tag_1['trid'] . ' ' . $tag_2['trid'] . '</br>';
    
    // The two tags have a different trid : abort
    if ($tag_1['trid'] != 0 && $tag_2['trid'] != 0 && $tag_1['trid'] != $tag_2['trid']){
        echo '<span style="padding-left:40px;color:red;">WARNING : "' . $tag_1['name'] . '" and "' . $tag_2['name'] . '" already have separate translation sets. They will not be set, you should do it by hand.</span></br>';
        continue;
    }
    // None of the tags have a trid : create one and set it
    if ($tag_1['trid']==0 && $tag_2['trid']==0){
        $q_str = "SELECT max(trid) FROM term_data";
        $result = mysql_query($q_str);
        $result = mysql_fetch_assoc($result);
        $last_trid = $result['max(trid)'];
        $trid = $last_trid + 1;
    // one term already has a trid, set the other to it.
    }elseif(($tag_1['trid']!=0 && $tag_2['trid']==0) || ($tag_1['trid']==0 && $tag_2['trid']!=0)){
        $trid = $tag_1['trid'] ? $tag_1['trid'] : $tag_2['trid'];
    // trid is already set right
    }else{
        echo 'Trid is already set to ' . $tag_1['trid'] . '</br>';
        continue;
    }
    
    echo 'Trid to set : ' . $trid . '</br>';
    
    // set the term_data for both terms
    /*$q_str = "UPDATE term_data SET trid={$trid} WHERE tid={$tag_1['tid']}";
    mysql_query($q_str);
    $q_str = "UPDATE term_data SET trid={$trid} WHERE tid={$tag_2['tid']}";
    mysql_query($q_str);
    echo 'UPDATED</br>';
        
    // check results : 
    $q_str = "SELECT trid from term_data WHERE tid={$tag_1['tid']}";
    $result = mysql_query($q_str);
    $row = mysql_fetch_assoc($result);
    echo $row['trid'] . ' ';
    $q_str = "SELECT trid from term_data WHERE tid={$tag_2['tid']}";
    $result = mysql_query($q_str);
    $row = mysql_fetch_assoc($result);
    echo $row['trid'] . '</br>'; */
}


echo '<form method="post" action="build_translation_sets_terms_real.php">
    <input type="hidden" name="language_1" value="' . $tags_to_set['language_1'] . '" />
    <input type="hidden" name="language_2" value="' . $tags_to_set['language_2'] . '" />
    <input type="hidden" name="first_tid_1" value="' . $tags_to_set['first_tid_1'] . '" />
    <input type="hidden" name="first_tid_2" value="' . $tags_to_set['first_tid_2'] . '" />
    <input type="hidden" name="last_tid_1" value="' . $tags_to_set['last_tid_1'] . '" />
    <input type="hidden" name="last_tid_2" value="' . $tags_to_set['last_tid_2'] . '" />
    <input type="submit" name="Submit" value="Set these translations" />
    </form>';

echo '<form method="post" action="term_translation_form.php">
    <input type="hidden" name="language_1" value="' . $tags_to_set['language_1'] . '" />
    <input type="hidden" name="language_2" value="' . $tags_to_set['language_2'] . '" />
    <input type="hidden" name="first_tid_1" value="' . $tags_to_set['first_tid_1'] . '" />
    <input type="hidden" name="first_tid_2" value="' . $tags_to_set['first_tid_2'] . '" />
    <input type="hidden" name="last_tid_1" value="' . $tags_to_set['last_tid_1'] . '" />
    <input type="hidden" name="last_tid_2" value="' . $tags_to_set['last_tid_2'] . '" />
    <input type="submit" name="Back" value="Go back to selecting terms" />
    </form>';


?>
