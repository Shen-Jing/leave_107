<?
if (isset($_POST['o_url']) && isset($_POST['n_url']))
{
  $o = substr($_POST['o_url'], 0, -3);
  $n = substr($_POST['n_url'], 0, -3);
  if (file_exists('../'.$o.'php') && file_exists('../js/'.$o.'js'))
    echo 'exists';
  if (copy('../'.$o.'php', '../'.$n.'php') && copy('../js/'.$o.'js', '../js/'.$n.'js'))
    echo 'success';

  //copy('../all.php', '../all_c.php');
}
