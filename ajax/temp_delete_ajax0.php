<?
if (isset($_POST['file']))
{
  $f = $_POST['file'];
  if (unlink('../template/php/'.$f.'.php') && unlink('../template/js/'.$f.'.js'))
    echo 'success';
}
