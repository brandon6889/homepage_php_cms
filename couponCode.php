<?php

if ($_GET['id'] != '') {
  $couponId = intval($_GET['id']);
  if (!file_exists('coupons/'.$couponId.'.txt'))
    // Should serve a default image..
    header('Location: /coupons.php');

  $couponData = file_get_contents('coupons/'.$couponId.'.txt');
  $couponFields = explode(']][[', $couponData);
  $couponBarcode = $couponFields[5];
  $code_string = "";

  $code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
  $upper_text = strtoupper($couponBarcode);
  for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
    $code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
  }
  $code_string = "1211212111" . $code_string . "121121211";

  $code_length = 20;
  for ( $i=1; $i <= strlen($code_string); $i++ )
    $code_length = $code_length + (integer)(substr($code_string,($i-1),1));

  $img_width = $code_length;
  $img_height = 12;

  $image = imagecreate($img_width, $img_height);
  $black = imagecolorallocate ($image, 0, 0, 0);
  $white = imagecolorallocate ($image, 255, 255, 255);

  imagefill( $image, 0, 0, $white );

  $location = 10;
  for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
    $cur_size = $location + ( substr($code_string, ($position-1), 1) );
    imagefilledrectangle( $image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black) );
    $location = $cur_size;
  }
  header ('Content-type: image/png');
  imagepng($image);
  exit;
}

header('Location: /coupons.php');

?>
