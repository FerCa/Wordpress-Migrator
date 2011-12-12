<?php

//**************************************************************//
// Wordpress Migrator
// A simple script to migrate any blog or website to wordpress.
// Author: FerCa 
// Date: 12/12/2011
//**************************************************************//


error_reporting(E_NOTICES);

// Connection variables declaration
$hostMysql = 'localhost';
$userMysql = 'user';
$passMysql = 'pass';
$dbMysqlWordpress = 'dbwordpress';
$dbMysqlActual = 'dbactual';
$urlWordpress = 'http://www.yourwordpressdomain.com/';
// Id for the next insert in wp_posts table (wordpress)
$startingId = 4;

$dbh_actual = mysql_connect($hostMysql, $userMysql, $passMysql);
mysql_select_db($dbMysqlActual,$dbh_actual);

echo 'Preparing to query actual database... <br>';
// You will need to adapt this query to your current web/blog
$result = mysql_query('select * from content order by data desc',$dbh_actual);

echo 'Iterating content... <br>';
$dbh_wordpress = mysql_connect($hostMysql, $userMysql, $passMysql);
mysql_select_db($dbMysqlWordpress,$dbh_wordpress);
while ($row = mysql_fetch_array($result)) {

    // Getting data from your current web/blog (you will need to tune this too)
    $date = $row['date'];
    $post_title = $row['title'];
    $post_name = str_replace(' ', '-', $post_title);
    $post_name = htmlspecialchars($post_name);
    $post_content = $row['content'];
    $post_content = mysql_real_escape_string($post_content);
    
    // Usefull to debug 
    /*echo '<br><br><br>____________________________________________________<br>';
    echo '<br><br><br>____________________________________________________<br>';
    echo 'Encoding: ';
    echo mb_detect_encoding($post_content);
    echo '<br>____________________________________________________________<br>';
    var_dump($date);
    echo '<br>____________________________________________________________<br>';
    var_dump($post_title);
    echo '<br>____________________________________________________________<br>';
    var_dump($post_name);
    echo '<br>____________________________________________________________<br>';
    var_dump($post_content);
    echo '<br>____________________________________________________________<br>';*/
   
    $query = "insert into wp_posts (post_author,post_date,post_date_gmt,post_content,post_title,post_excerpt,post_status,comment_status,ping_status,post_password,post_name,to_ping,pinged,post_modified,post_modified_gmt,post_content_filtered,post_parent,guid,menu_order,post_type,post_mime_type,comment_count) VALUES (1,'" . $date . "','" . $date . "','" . $post_content . "','" . $post_title . "','','publish','open','open','','" . $post_name . "','','','" . $date . "','" . $date . "','',0,'" . $urlWordpress . "/?p=" . $startingId . "',0,'post','',0)";
    $result2 = mysql_query($query,$dbh_wordpress);

    echo '<br> Migrating post: ' . $startingId;
    
    $startingId += 1;
}

echo 'Migration finished! :)';


?>
