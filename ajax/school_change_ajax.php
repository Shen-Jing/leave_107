<?
session_start();
include "../inc/connect.php";

if($_POST['id'] > 0 ) 
{       
    $sql="select title_name,school_id,year from title t where school_id='".$_POST['id'] ."' order by year desc "; 
	$d = $db -> query_array($sql);                  
    $res = array('school_id'=>$d['SCHOOL_ID'][0],'title_name'=>$d['TITLE_NAME'][0],'year'=>$d['YEAR'][0]);
    $_SESSION['school_id'] = $d['SCHOOL_ID'][0];
    $_SESSION['title_name'] = $d['TITLE_NAME'][0];
    $_SESSION['year'] = $d['YEAR'][0];
    echo json_encode($res);
}
//echo "test" . $results['TITLE_NAME'][0] ;
?>