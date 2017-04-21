<?
if (isset($_POST['file']))
{
  $f = $_POST['file'];
  if (copy('../'.$f.'.php', '../template/php/'.$f.'.php') && copy('../js/'.$f.'.js', '../template/js/'.$f.'.js'))
    echo 'success';
}
