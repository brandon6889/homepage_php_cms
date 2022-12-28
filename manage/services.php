<?php

define('IN_ADM', true);

require ('config.php');

if (!LOGGED_IN) {
  header('Location: /manage/');
  exit;
}

// from stack overflow
//function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
//    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
//}
//function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
//    return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
//}

if (isset($_GET['delete'])) {
  $i = intval($_GET['delete']);
  try {
    if (file_exists('../services_txt/'.$i.'.txt')) {
      //$serviceData = file_get_contents('../services_txt/'.$i.'.txt');
      //$serviceData = '1'.substr($serviceData, 1);
      //file_put_contents('../services_txt/'.$i.'.txt', $serviceData);
      rename('../services_txt/'.$i.'.txt', '../services_txt_delete/'.$i.'.txt');
      header('Location: /manage/services.php');
    }
  } catch (Exception $e) {
    die ('Unable to remove services page. Contact bam@npmga.tk');
  }
}

if (isset($_GET['id'])) {
  $serviceId = intval($_GET['id']);
  if ($serviceId == 0)
    $newService = true;
  elseif (file_exists('../services_txt/'.$serviceId.'.txt'))
    $serviceValid = true;

  if (isset($serviceValid) && isset($_POST['title'])) {
    // update page
    $serviceData = $_POST['background']."\n]][[\n".$_POST['title']."\n]][[\n".$_POST['lefttext']."\n]][[\n".$_POST['righttext']."\n]][[\n".$_POST['bottomnote'];
    try {
      file_put_contents('../services_txt/'.$serviceId.'.txt', $serviceData);
    } catch (Exception $e) {
      die('Unable to modify services on server. Contact bam@npmga.tk');
    }
    
    // Remove old service page render
    $oldRenders = scandir('../services/');
    unset($oldRenders[0]);
    unset($oldRenders[1]);
    $sid = strval($serviceId);
    
    foreach ($oldRenders as $oldRender) {
      if (strrpos($oldRender, $sid, -strlen($oldRender)) !== FALSE) {
        unlink ('../services/'.$oldRender);
        break;
      }
    }
    
    // Create new service page render
    $renderExt = strrchr($_POST['background'],'.');
    
    $render = new Imagick();
    $render->readImage('../services_bg/'.$_POST['background']); // security pls
    $render->resizeImage(1065,804,Imagick::FILTER_CATROM,1,true);
    
    $drawNormal = new ImagickDraw();
    $drawNormal->setFillColor('black');
    $drawNormal->setFont('Calibri.ttf');
    
    $drawBold = new ImagickDraw();
    $drawBold->setFillColor('black');
    $drawBold->setFont('Calibri_Bold.ttf');
    
    $drawItalic = new ImagickDraw();
    $drawItalic->setFillColor('black');
    $drawItalic->setFont('Calibri_Italic.ttf');
    
    $drawBoldItalic = new ImagickDraw();
    $drawBoldItalic->setFillColor('black');
    $drawBoldItalic->setFont('Calibri_Bold_Italic.ttf');
    $lineSpacing = 48; // pixels per line
    
    // draw title at North offset x0, y20, angle0
    $drawBold->setFontSize( 54 );
    $drawBold->setGravity(Imagick::GRAVITY_NORTH);
    $render->annotateImage($drawBold, 0, 20, 0, $_POST['title']);
    
    // draw left column
    $drawBold      ->setFontSize( 29 );
    $drawNormal    ->setFontSize( 29 );
    $drawItalic    ->setFontSize( 29 );
    $drawBoldItalic->setFontSize( 29 );
    $drawBold      ->setGravity(Imagick::GRAVITY_NORTHWEST);
    $drawNormal    ->setGravity(Imagick::GRAVITY_NORTHWEST);
    $drawItalic    ->setGravity(Imagick::GRAVITY_NORTHWEST);
    $drawBoldItalic->setGravity(Imagick::GRAVITY_NORTHWEST);
    $lineNumber = 0;
    $lines = explode("\n", $_POST['lefttext']);
    foreach ($lines as $line) {
      $lineNumber = $lineNumber + 1;
      if ($line == '') continue;
      if (strrpos($line, '/*', -strlen($line)) !== FALSE || strrpos($line, '*/', -strlen($line)) !== FALSE) {
        $line = substr($line, 2);
        $render->annotateImage($drawBoldItalic, 40, 48 + $lineNumber * $lineSpacing, 0, $line);
      } elseif (strrpos($line, '*', -strlen($line)) !== FALSE) {
        $line = substr($line, 1);
        $render->annotateImage($drawBold, 40, 48 + $lineNumber * $lineSpacing, 0, $line);
      } elseif (strrpos($line, '/', -strlen($line)) !== FALSE) {
        $line = substr($line, 1);
        $render->annotateImage($drawItalic, 40, 48 + $lineNumber * $lineSpacing, 0, $line);
      } else {
        $render->annotateImage($drawNormal, 40, 48 + $lineNumber * $lineSpacing, 0, $line);
      }
    }
    
    // draw right column
    $lineNumber = 0;
    $lines = explode("\n", $_POST['righttext']);
    foreach ($lines as $line) {
      $lineNumber = $lineNumber + 1;
      if ($line == '') continue;
      if (strrpos($line, '/*', -strlen($line)) !== FALSE || strrpos($line, '*/', -strlen($line)) !== FALSE) {
        $line = substr($line, 2);
        $render->annotateImage($drawBoldItalic, 592, 48 + $lineNumber * $lineSpacing, 0, $line);
      } elseif (strrpos($line, '*', -strlen($line)) !== FALSE) {
        $line = substr($line, 1);
        $render->annotateImage($drawBold, 592, 48 + $lineNumber * $lineSpacing, 0, $line);
      } elseif (strrpos($line, '/', -strlen($line)) !== FALSE) {
        $line = substr($line, 1);
        $render->annotateImage($drawItalic, 592, 48 + $lineNumber * $lineSpacing, 0, $line);
      } else {
        $render->annotateImage($drawNormal, 592, 48 + $lineNumber * $lineSpacing, 0, $line);
      }
    }
    
    // draw bottom note
    $drawItalic->setGravity(Imagick::GRAVITY_SOUTH);
    $render->annotateImage($drawItalic, 0, 20, 0, $_POST['bottomnote']);
    
    $render->writeImage('../services/'.$serviceId.'_'.rand(100,999).$renderExt);
    $render->clear();
    $render->destroy();

    header('Location: /manage/services.php');
    exit;
  }
  elseif (isset($newService) && isset($_POST['title'])) {
    $serviceData = $_POST['background']."\n]][[\n".$_POST['title']."\n]][[\n".$_POST['lefttext']."\n]][[\n".$_POST['righttext']."\n]][[\n".$_POST['bottomnote'];

    for ($i = 100; $i <= 999; $i++) {
      if (!file_exists('../services_txt/'.strval($i).'.txt') && !file_exists('../services_txt_delete/'.strval($i).'.txt'))
        break;
    }

    try {
      file_put_contents('../services_txt/'.strval($i).'.txt', $serviceData);
    } catch (Exception $e) {
      die('Unable to add services to server. Contact bam@npmga.tk');
    }

    header ('Location: /manage/services.php');
    exit;
  }
}

if (isset($_GET['moveUp'])) {
  $services = scandir('../services_txt/');
  unset($services[0]);
  unset($services[1]);
  $i = intval($_GET['moveUp']);
  if ($i > 1 && $i < count($services) + 1) {
    $selectedServices = $services[$i + 1];
    $replacedServices = $services[$i];
    
    try {
      rename ('../services_txt/'.$replacedServices, '../services_txt/tmp.bak');
      rename ('../services_txt/'.$selectedServices, '../services_txt/'.$replacedServices);
      rename ('../services_txt/tmp.bak', '../services_txt/'.$selectedServices);

      header ('Location: /manage/services.php');
    } catch (Exception $e) {
      die('Unable to move file on the server. Contact bam@npmga.tk');
    }
  }
}

if (isset($_GET['moveDown'])) {
  $services = scandir('../services_txt/');
  unset($services[0]);
  unset($services[1]);
  $i = intval($_GET['moveDown']);
  if ($i > 0 && $i < count($services)) {
    $selectedServices = $services[$i + 1];
    $replacedServices = $services[$i + 2];
    
    try {
      rename ('../services_txt/'.$replacedServices, '../services_txt/tmp.bak');
      rename ('../services_txt/'.$selectedServices, '../services_txt/'.$replacedServices);
      rename ('../services_txt/tmp.bak', '../services_txt/'.$selectedServices);

      header ('Location: /manage/services.php');
    } catch (Exception $e) {
      die('Unable to move file on the server. Contact bam@npmga.tk');
    }
  }
}

require('head.php');

if (isset($serviceValid)) {
  $serviceData = file_get_contents('../services_txt/'.$serviceId.'.txt');
  $serviceFields = explode("\n]][[\n", $serviceData);
  echo '        <h1>Modify Services</h1>
        <hr />
        <p>Formatting notes:</p>
        <p> - Start a line with a / to make it italicized</p>
        <p> - Start a line with a * to make it bold</p>
        <br />
        <form action="?id='.$serviceId.'" method="post" class="services" accept-charset="UTF-8">
          <fieldset>
            <legend>Services - '.$serviceFields[1].'</legend>
            <div>
              <label for="background">Background image #</label>
              <select name="background" id="background" onchange="var image=$(\'#background option:selected\').val(); image = \'/services_bg/\'.concat(image); $(\'#selected\').attr(\'src\', image)">
';
  $backgrounds = scandir('../services_bg/');
  unset($backgrounds[0]);
  unset($backgrounds[1]);
  $i = 1;
  foreach ($backgrounds as $background) {
    echo '                <option value="'.$background.'"';
    if ($serviceFields[0] == $background) echo ' selected';
    echo '>'.strval($i).'</option>
';
    $i = $i + 1;
  }
  echo '              </select> &nbsp; <img id="selected" src="/services_bg/'.$serviceFields[0].'" width="160" height="100" />
            </div>
            <br />
            <table>
              <tr>
                <td colspan="2" width="560px" align="center">
                  <input type="text" name="title" id="title" maxlength="30" class="top" value="'.$serviceFields[1].'" />
                </td>
              </tr>
              <tr>
                <td>
                  <textarea name="lefttext" id="lefttext" rows="20">'.$serviceFields[2].'</textarea>
                </td>
                <td>
                  <textarea name="righttext" id="righttext" rows="20">'.$serviceFields[3].'</textarea>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="text" name="bottomnote" id="bottomnote" maxlength="100" class="bottom" value="'.$serviceFields[4].'" />
                </td>
              </tr>
            </table>
            <div style="text-align:center; min-width:100%;">
              <input name="submit" class="submit" type="submit" />
              <p>Please click once and wait patiently for the changes to apply.</p>
            </div>
          </fieldset>
        </form>
        <br />';

} elseif (isset($newService)) {
  echo '        <h1>Modify Services</h1>
        <hr />
        <p>Formatting notes:</p>
        <p> - Start a line with a / to make it italicized</p>
        <p> - Start a line with a * to make it bold</p>
        <br />
        <form action="?id=0" method="post" class="services" accept-charset="UTF-8">
          <fieldset>
            <legend>Services</legend>
            <div>
              <label for="background">Background image #</label>
              <select name="background" id="background">
';
  $backgrounds = scandir('../services_bg/');
  unset($backgrounds[0]);
  unset($backgrounds[1]);
  $i = 1;
  foreach ($backgrounds as $background) {
    echo '                <option value="'.$background.'">'.strval($i).'</option>
';
    $i = $i + 1;
  }
  echo '              </select>
            </div>
            <br />
            <table>
              <tr>
                <td colspan="2" width="560px" align="center">
                  <input type="text" name="title" id="title" maxlength="30" class="top" placeholder="title" />
                </td>
              </tr>
              <tr>
                <td>
                  <textarea name="lefttext" id="lefttext" rows="20"></textarea>
                </td>
                <td>
                  <textarea name="righttext" id="righttext" rows="20"></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input type="text" name="bottomnote" id="bottomnote" maxlength="100" class="bottom" placeholder="Bottom note -- optional" />
                </td>
              </tr>
            </table>
            <div style="text-align:center; min-width:100%;">
              <input name="submit" class="submit" type="submit" />
            </div>
          </fieldset>
        </form>
        <br />';

} else {
  echo '        <h1>Services</h1>
        <p><a href="?id=0">Add new Service Page</a></p>
        <p><a href="services_bg.php">Manage background images</a></p>
        <hr />
        <h3>Existing Service Pages</h3>
        <table class="listTable">
';

  $services = scandir('../services_txt/');
  unset($services[0]);
  unset($services[1]);
  
  $i = 1;

  foreach ($services as $service) {
    $serviceData = file_get_contents('../services_txt/'.$service);
    $serviceFields = explode("\n]][[\n", $serviceData);
    $serviceId = substr($service, 0, strpos($service, '.'));

    if (isset($serviceFields[1])) {
      echo '          <tr><td>
            <div style="height: 80px; position:relative; top: 30px; left: 15px;">'.$serviceFields[1].'</div>
              <table style="top: 30px;"><tr>
                ';
      if ($i != count($services)) echo '<td><a href="?moveDown='.$i.'"><img src="/static/nav_south_up.png" /></a></td>
                ';
      if ($i != 1) echo '<td><a href="?moveUp='.$i.'"><img src="/static/nav_north_up.png" /></a></td>
                ';
      else echo '<td><img src="/static/spacer.png" /></td>
                ';
      echo '<td><a href="?id='.$serviceId.'"><img src="/static/edit.gif" /></a></td>
                <td><a href="?delete='.$serviceId.'"><img src="/static/delete.png" /></a></td>
              </tr></table>
          </td></tr>
';

    }
    
    $i = $i + 1;
  }
  echo '        </table>';
}

?>

<?php require ('foot.html'); ?>
