<?php

define('IN_ADM', true);

require ('config.php');

if (!LOGGED_IN) {
  header('Location: /manage/');
}

if (isset($_GET['delete'])) {
  $couponId = intval($_GET['delete']);
  if (file_exists('../coupons/'.$couponId.'.txt')) {
    $couponData = file_get_contents('../coupons/'.$couponId.'.txt');
    $couponData = '1'.substr($couponData, 1);
    try {
      file_put_contents('../coupons/'.$couponId.'.txt', $couponData);
    } catch (Exception $e) {
      die('Unable to write coupon to server. Contact bam@npmga.tk');
    }

    header ('Location: /manage/coupons.php');
  }
}

if (isset($_GET['id'])) {
  $couponId = intval($_GET['id']);
  if ($couponId == 0)
    $newCoupon = true;
  elseif (file_exists('../coupons/'.$couponId.'.txt'))
    $couponValid = true;

  if (isset($couponValid) && isset($_POST['title'])) {
    // update coupon
    $couponData = '0]][['.$_POST['title'].']][['.$_POST['shorttext'].']][['.$_POST['longtext'].']][['.$_POST['expires'].']][['.$_POST['barcode'];
    try {
      file_put_contents('../coupons/'.$couponId.'.txt', $couponData);
    } catch (Exception $e) {
      die('Unable to write coupon to server. Contact bam@npmga.tk');
    }

    header('Location: /manage/coupons.php');
    exit;
  }
  elseif (isset($newCoupon) && isset($_POST['title'])) {
    $couponData = '0]][['.$_POST['title'].']][['.$_POST['shorttext'].']][['.$_POST['longtext'].']][['.$_POST['expires'].']][['.$_POST['barcode'];

    for ($i = 1000; $i <= 9999; $i++) {
      if (!file_exists('../coupons/'.strval($i).'.txt'))
        break;
    }

    try {
      file_put_contents('../coupons/'.strval($i).'.txt', $couponData);
    } catch (Exception $e) {
      die('Unable to write coupon to server. Contact bam@npmga.tk');
    }

    header ('Location: /manage/coupons.php');
    exit;
  }
}

require('head.php');

if (isset($couponValid)) {
  $couponData = file_get_contents('../coupons/'.$couponId.'.txt');
  $couponFields = explode(']][[', $couponData);
  echo '        <h1>Modify Coupon</h1>
        <hr /><br /><br />
        <form action="?id='.$couponId.'" method="post" class="coupon" accept-charset="UTF-8">
          <fieldset>
            <legend>Coupon</legend>
            <div class="row">
              <label for="title">Title</label>
              <input type="text" name="title" id="title" maxlength="30" value="'.$couponFields[1].'" />
            </div>
            <div class="row">
              <label for="shorttext">Website Text</label>
              <input type="text" name="shorttext" id="shorttext" maxlength="60" value="'.$couponFields[2].'" />
            </div>
            <div class="row">
              <label for="longtext">Extra Print Text</label>
              <input type="text" name="longtext" id="longtext" maxlength="100" value="'.$couponFields[3].'" />
            </div>
            <div class="row">
              <label for="expires">Expiration Date</label>
              <input type="text" name="expires" id="expires" maxlength="20" value="'.$couponFields[4].'" />
            </div>
            <div class="row">
              <label for="barcode">Barcode Data</label>
              <input type="text" name="barcode" id="barcode" maxlength="30" value="'.$couponFields[5].'" />
            </div>
            <div style="text-align:center; min-width:100%;">
              <input name="submit" class="submit" type="submit" />
            </div>
          </fieldset>
        </form>';

} elseif (isset($newCoupon)) {
  echo '        <h1>New Coupon</h1>
        <hr /><br /><br />
        <form action="?id=0" method="post" class="coupon" accept-charset="UTF-8">
          <fieldset>
            <legend>Coupon</legend>
            <div class="row">
              <label for="title">Title</label>
              <input type="text" name="title" id="title" maxlength="30" />
            </div>
            <div class="row">
              <label for="shorttext">Website Text</label>
              <input type="text" name="shorttext" id="shorttext" maxlength="60" />
            </div>
            <div class="row">
              <label for="longtext">Extra Print Text</label>
              <input type="text" name="longtext" id="longtext" maxlength="100" />
            </div>
            <div class="row">
              <label for="expires">Expiration Date</label>
              <input type="text" name="expires" id="expires" maxlength="20" />
            </div>
            <div class="row">
              <label for="barcode">Barcode Data</label>
              <input type="text" name="barcode" id="barcode" maxlength="30" />
            </div>
            <div style="text-align:center; min-width:100%;">
              <input name="submit" class="submit" type="submit" />
            </div>
          </fieldset>
        </form>';

} else {
  echo '        <h1>Coupons</h1>
        <a href="?id=0"><img src="/static/add.png" /> Add a new coupon</a><br />&nbsp;
        <hr />
        <div class="couponContainer">
';

  $coupons = scandir('../coupons/');
  unset($coupons[0]);
  unset($coupons[1]);

  foreach ($coupons as $coupon) {
    $couponData = file_get_contents('../coupons/'.$coupon);
    $couponFields = explode(']][[', $couponData);
    $couponId = substr($coupon, 0, strpos($coupon, '.'));

    if (isset($couponFields[1]) && intval($couponFields[0]) == 0)
      echo '          <div class="coupon"><div class="coupon_spacer"></div><div class="coupon_content"><h1>'.$couponFields[1].'</h1><p>'.$couponFields[2].'</p></div></div>
          <div class="deleteCoupon">
            <a href="?id='.$couponId.'">Edit coupon <img src="/static/edit.gif" /></a><br />
            <a href="?delete='.$couponId.'">Remove coupon <img src="/static/delete.png" /></a>
          </div>
';
  }
  echo '        </div>';
}

?>

<?php require ('foot.html'); ?>
