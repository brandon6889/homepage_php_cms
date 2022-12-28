<?php

define('IN_ADM', true);

require ('config.php');

if (!LOGGED_IN) {
  header('Location: /manage/');
  exit;
}

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

if (isset($_GET['delete'])) {
  $images = scandir('../gallery/');
  unset($images[0]);
  unset($images[1]);
  $i = intval($_GET['delete']);
  if ($i > 0 && $i < count($images) + 1) {
    try {
      unlink('../gallery/'.$images[$i + 1]);
      unlink('../gallery_thumb/'.$images[$i + 1]);
      header('Location: /manage/photos.php');
      exit;
    } catch (Exception $e) {
      die('Unable to remove file from server. Contact bam@npmga.tk');
    }
  }
}

if (isset($_GET['moveUp'])) {
  $images = scandir('../gallery/');
  unset($images[0]);
  unset($images[1]);
  $i = intval($_GET['moveUp']);
  if ($i > 1 && $i < count($images) + 1) {
    // randomize the ending of the hash filenames in order to beat caches..
    $randomEnding = strval(rand(100,999));

    $selectedImage = $images[$i + 1];
    $selectedBase = substr($selectedImage, 0, strrpos($selectedImage,'.'));
    $selectedBase = substr($selectedBase, 0, strlen($selectedBase) - 3).$randomEnding;
    $selectedExt = substr(strrchr($selectedImage,'.'),1);

    $replacedImage = $images[$i];
    $replacedBase = substr($replacedImage, 0, strrpos($replacedImage,'.'));
    $replacedBase = substr($replacedBase, 0, strlen($replacedBase) - 3).$randomEnding;
    $replacedExt = substr(strrchr($replacedImage,'.'),1);
    try {
      rename ('../gallery/'.$replacedImage, '../gallery/tmp.'.$replacedExt);
      rename ('../gallery/'.$selectedImage, '../gallery/'.$replacedBase.'.'.$selectedExt);
      rename ('../gallery/tmp.'.$replacedExt, '../gallery/'.$selectedBase.'.'.$replacedExt);

      rename ('../gallery_thumb/'.$replacedImage, '../gallery_thumb/tmp.'.$replacedExt);
      rename ('../gallery_thumb/'.$selectedImage, '../gallery_thumb/'.$replacedBase.'.'.$selectedExt);
      rename ('../gallery_thumb/tmp.'.$replacedExt, '../gallery_thumb/'.$selectedBase.'.'.$replacedExt);

      header ('Location: /manage/photos.php');
    } catch (Exception $e) {
      die('Unable to move file on the server. Contact bam@npmga.tk');
    }
  }
}

if (isset($_GET['moveDown'])) {
  $images = scandir('../gallery/');
  unset($images[0]);
  unset($images[1]);
  $i = intval($_GET['moveDown']);
  if ($i > 0 && $i < count($images)) {
    // randomize the ending of the hash filenames in order to beat caches..
    $randomEnding = strval(rand(100,999));

    $selectedImage = $images[$i + 1];
    $selectedBase = substr($selectedImage, 0, strrpos($selectedImage,'.'));
    $selectedBase = substr($selectedBase, 0, strlen($selectedBase) - 3).$randomEnding;
    $selectedExt = substr(strrchr($selectedImage,'.'),1);

    $replacedImage = $images[$i + 2];
    $replacedBase = substr($replacedImage, 0, strrpos($replacedImage,'.'));
    $replacedBase = substr($replacedBase, 0, strlen($replacedBase) - 3).$randomEnding;
    $replacedExt = substr(strrchr($replacedImage,'.'),1);
    try {
      rename ('../gallery/'.$replacedImage, '../gallery/tmp.'.$replacedExt);
      rename ('../gallery/'.$selectedImage, '../gallery/'.$replacedBase.'.'.$selectedExt);
      rename ('../gallery/tmp.'.$replacedExt, '../gallery/'.$selectedBase.'.'.$replacedExt);

      rename ('../gallery_thumb/'.$replacedImage, '../gallery_thumb/tmp.'.$replacedExt);
      rename ('../gallery_thumb/'.$selectedImage, '../gallery_thumb/'.$replacedBase.'.'.$selectedExt);
      rename ('../gallery_thumb/tmp.'.$replacedExt, '../gallery_thumb/'.$selectedBase.'.'.$replacedExt);

      // we can actually continue, but we should remove the GET so back button doesn't change things..
      header ('Location: /manage/photos.php');
    } catch (Exception $e) {
      die('Unable to move file on the server. Contact bam@npmga.tk');
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
      if (!move_uploaded_file($_FILES['file']['tmp_name'], sprintf('../gallery/%s.%s', $safeName, $ext))) {
        die ('Unable to write file to server. Contact bam@npmga.tk');
      }

      $thumbnail = new Imagick();
      $thumbnail->readImage('../gallery/'.$safeName.'.'.$ext);
      $thumbnail->resizeImage(80,80,Imagick::FILTER_CATROM,1,true);
      $thumbnail->writeImage('../gallery_thumb/'.$safeName.'.'.$ext);
      $thumbnail->clear();
      $thumbnail->destroy();
      
      break;
    case UPLOAD_ERR_NO_FILE: // do nothing, continue POST
      break;
    default:
      die('Error uploading file. Press back and try again. Maybe the filename has incompatible characters or spaces.');
  }

  
}

require('head.php');

?>

<h1>Photo Gallery</h1>

<form class="upload" action="photos.php" method="post" enctype="multipart/form-data">
  <legend>Upload New Photo</legend>

  <input type="file" name="file" id="file" />
  <input type="submit" value="Upload" name="submit" />
</form>
<p>Once you click upload, please wait patiently for the upload to complete.</p>

<hr />

<h3>Existing Photos in Gallery</h3>

<table class="listTable">
<?php

$photos = scandir('../gallery');
unset($photos[0]);
unset($photos[1]);

$i = 1;

foreach ($photos as $photo) {
  echo '  <tr><td>
    <img src="/gallery/'.$photo.'" class="full" />
    <img src="/gallery_thumb/'.$photo.'" class="thumb" />
    <table><tr>';
  if ($i != count($photos)) echo '
      <td><a href="?moveDown='.$i.'"><img src="/static/nav_south_up.png" /></a></td>';
  if ($i != 1) echo '
      <td><a href="?moveUp='.$i.'"><img src="/static/nav_north_up.png" /></a></td>';
  else { echo '
      <td><img src="/static/spacer.png" /></td>';
  }
  echo '
      <td><a href="?delete='.$i.'"><img src="/static/delete.png" /></a></td>
     </tr></table>
  </td></tr>';
  $i = $i + 1;
}

echo "</table>\n";

require ('foot.html');

?>
