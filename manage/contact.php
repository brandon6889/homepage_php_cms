<?php

define('IN_ADM', true);

require ('config.php');

if (!LOGGED_IN) {
  header('Location: /manage/');
}

if (isset($_POST['top'])) {

  file_put_contents('../top.txt', $_POST['top']);
  file_put_contents('../address.txt', $_POST['address']);
  file_put_contents('../hours.txt', $_POST['hours']);
  file_put_contents('../about.txt', $_POST['about']);
  file_put_contents('../gmaplink.txt', $_POST['gmaplink']);

  header('Location: /manage/contact.php?set');
  exit;

} elseif(isset($_GET['map']) && $_FILES['file']['size'] > 0) {

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

      $oldMap = scandir('../google_map/');
      try {
        unlink('../google_map/'.$oldMap[2]);
      } catch (Exception $e) {}

      $newMap = 'map_'.strval(rand(1000,9999));
      if (!move_uploaded_file($_FILES['file']['tmp_name'], sprintf('../google_map/%s.%s', $newMap, $ext))) {
        die ('Unable to write file to server. Contact bam@npmga.tk');
      }

      header('Location: /manage/contact.php?set');
      exit;

    case UPLOAD_ERR_NO_FILE: // do nothing, continue POST
      break;
    default:
      die('Error uploading file. Press back and try again. Maybe the filename has incompatible characters or spaces.');
  }

} else {

require ('head.php');

$topData = file_get_contents('../top.txt');
$addressData = file_get_contents('../address.txt');
$hoursData = file_get_contents('../hours.txt');
$aboutData = file_get_contents('../about.txt');
$gmaplinkData = file_get_contents('../gmaplink.txt');

$googleMap = scandir('../google_map/');
$googleMap = $googleMap[2];

echo '        <h1>Contact Information</h1>
';

if (isset($_GET['set']))
  echo '        <p>Saved successfully</p>
';
else echo '        <br />
';

echo '        <hr /><br /><br />
        <form action="?" method="post" class="contact" accept-charset="UTF-8">
          <fieldset>
            <legend>Store Information</legend>
            <div class="row">
              <label for="top">Top Info</label>
              <textarea name="top" id="top" maxlength="100" rows="3">'.$topData.'</textarea>
            </div>
            <div class="row">
              <label for="address">Store Address</label>
              <textarea name="address" id="address" maxlength="140" rows="3">'.$addressData.'</textarea>
            </div>
            <div class="row">
              <label for="hours">Store Hours</label>
              <textarea name="hours" id="hours" maxlength="140" rows="4">'.$hoursData.'</textarea>
            </div>
            <div class="row">
              <label for="about">About Page Text</label>
              <textarea name="about" id="about" maxlength="160" rows="4">'.$aboutData.'</textarea>
            </div>
            <div class="row">
              <label for="gmaplink">Google Maps Link</label>
              <input type="text" name="gmaplink" id="gmaplink" value="'.$gmaplinkData.'">
            </div>
            <div style="text-align:center; min-width:100%;">
              <input name="submit" class="submit" type="submit" />
            </div>
          </fieldset>
        </form>
        <form class="upload" action="?map" method="post" enctype="multipart/form-data">
          <fieldset>
            <legend>Google Maps Image</legend>
            <img src="../google_map/'.$googleMap.'" style="width: 65%;" />
            <br />
            <input type="file" name="file" id="file" />
            <input type="submit" value="Upload" name="submit" />
          </fieldset>
        </form>';

}

require('foot.html');

?>
