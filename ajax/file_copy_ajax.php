<?
if (isset($_POST['o_url']) && isset($_POST['n_url']))
{
  $o = substr($_POST['o_url'], 0, -3);
  $n = substr($_POST['n_url'], 0, -3);
  if (copy('../template/php/'.$o.'php', '../'.$n.'php') && copy('../template/js/'.$o.'js', '../js/'.$n.'js'))
    echo 'success';
}
