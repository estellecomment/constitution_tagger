<?php

function get_fold_number($current_depth, $fold_numbers){
            $fold_number = "";
            for($i=0; $i<=$current_depth; $i++){
                $fold_number .= '_' . $fold_numbers[$i];
            }
            return substr($fold_number, 1);
}


// find the vid of the Tags vocabulary
function get_tags_vid(){
    $q_str = "SELECT vid FROM vocabulary WHERE name='Tags'";
    $result = mysql_query($q_str);
    $vidrow = mysql_fetch_assoc($result);
    $tags_vid = $vidrow['vid'];
    return $tags_vid;
}


function get_node_terms($nid, $tags_vid){
     $q_str = "SELECT term_node.tid from term_node, term_data WHERE term_node.tid = term_data.tid AND term_node.nid = $nid AND term_data.vid = $tags_vid;";
     $term_result = mysql_query($q_str);
     while ($row = mysql_fetch_assoc($term_result)) {
         $node_terms[] = $row['tid'];
     }
     return $node_terms;
}

function get_all_records($result, $indexby = NULL){
    if ($indexby){
        while($row = mysql_fetch_assoc($result)){
                $rows[$row[$indexby]] = $row;
        }
    }else{
        while($row = mysql_fetch_assoc($result)){
                $rows[] = $row;
        }
    }
    return $rows;
}

?>
