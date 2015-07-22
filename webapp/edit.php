<?php 

include 'core.php';

$id = -1;
$url = isset($_POST['url']) ? $_POST['url'] : '';
$evidence = '';
$alert = '';

if (isset($_POST['url']) && isset($_POST['evidence']))
{
  $url = $_POST['url'];
  $evidence = $_POST['evidence'];

  $hoax = ORM::for_table('hoax', 'remote')->where('url', $url);
  if ($hoax->count() == 0)
  {
    $hoax = ORM::for_table('hoax', 'remote')->create();

    $hoax->url = $url;
    $hoax->evidence = $evidence;

    $hoax->save();

  } else {
    $hoax = $hoax->find_one();
    
    // saving new information
    $id = $hoax->id;
    $hoax->evidence = $evidence;
    $hoax->save();

    $alert = "<div class=\"alert alert-warning\">Url already exists. Updating instead.</div>";
  }
}

if (isset($_GET['id']))
{
  $hoax = ORM::for_table('hoax', 'remote')->find_one($_GET['id']);
  $id = $hoax->id;
  $url = $hoax->url;
  $normalizer = new \URL\Normalizer();
  $normalizer->setUrl($url);
  $url = $normalizer->normalize();
  $evidence = $hoax->evidence;
}


echo $twig->render('edit.html', array( 
  'base' => $base,
  'url' => $url,
  'evidence' => $evidence,
  'alert' => $alert,
  'id' => $id
  )
);

?>