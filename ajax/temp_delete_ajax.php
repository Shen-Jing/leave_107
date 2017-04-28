<?
if (isset($_POST['file']))
{
  $f = $_POST['file'];
  if (unlink('../template/php/'.$f.'.php') && unlink('../template/js/'.$f.'.js') && unlink('../template/ajax/'.$f.'_ajax.php'))
    echo 'success';
  //unlink('../template/'.$f.'.png');
}
