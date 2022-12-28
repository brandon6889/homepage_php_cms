<?php

define('IN_ADM', true);

require ('config.php');

if (!LOGGED_IN) {
  header('Location: /manage/');
  exit;
}

if (isset($_GET['delete'])) {
  $images = scandir('../services_bg/');
  unset($images[0]);
  unset($images[1]);
  $i = intval($_GET['delete']);
  if ($i > 0 && $i < count($images) + 1) {
    try {
      unlink('../services_bg/'.$images[$i + 1]);
      header('Location: /manage/services_bg.php');
      exit;
    } catch (Exception $e) {
      die('Unable to remove file from server. Contact bam@npmga.tk');
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_FILES['file']['size'] > 0)
  switch ($_FILES['file']['error']) {
    case UPLOAD_ERR_OK:
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      if (false === $ext = array_search(
          $finfo->file($_FILES['file']['tmp_name']),
          array('jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
          ),
          true))
        die ('Invalid image file uploaded.');

      $safeName = md5(sha1_file($_FILES['file']['tmp_name']));
      if (!move_uploaded_file($_FILES['file']['tmp_name'], sprintf('../services_bg/%s.%s', $safeName, $ext))) {
        die ('Unable to write file to server. Contact bam@npmga.tk');
      }
      
      break;
    case UPLOAD_ERR_NO_FILE: // do nothing, continue POST
      break;
    default:
      die('Error uploading file. Press back and try again. Maybe the filename has incompatible characters or spaces.');
  }

  
}

require('head.php');

?>

<h1>Service Page Backgrounds</h1>

<hr />

<form class="upload" action="services_bg.php" method="post" enctype="multipart/form-data">
  <legend>Upload New Background Image</legend>

  <input type="file" name="file" id="file" />
  <input type="submit" value="Upload" name="submit" />
</form>
<p>Once you click upload, please wait patiently for the upload to complete.</p>
<p>Please be careful deleting backgrounds. Pages using them will need to be updated.</p>
<p><a href="/manage/services.php">Return to Service Pages</a></p>

<hr />

<h3>Existing Service Page Backgrounds</h3>

<table class="listTable">
<?php

$photos = scandir('../services_bg/');
unset($photos[0]);
unset($photos[1]);

$i = 1;

foreach ($photos as $photo) {
  echo '  <tr><td>
    <a href="/services_bg/'.$photo.'"><img src="/services_bg/'.$photo.'" class="full" /></a>
    <table><tr>
      <td><a href="?delete='.$i.'"><img src="/static/delete.png" /></a></td>
     </tr></table>
  </td></tr>';
  $i = $i + 1;
}

echo "</table>\n";

require ('foot.html');

?>
