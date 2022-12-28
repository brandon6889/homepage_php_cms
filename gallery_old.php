<?php
  require ('header.php');
  
  $pageNumber = $_GET['page'];
  
  // replace this with MySQL row count et cetera..
  if ($pageNumber != '0' && $pageNumber != '1' && $pageNumber != '2') {
    $pageNumber = 0;
  }
  
  $nextPage = $pageNumber + 1;
  $prevPage = $pageNumber - 1;
  
  $image1 = '0';
  $image2 = '0';
  $image3 = '0';
  $image4 = '0';
  //$image1 = $pageNumber * 4;
  //$image2 = $image1 + 1;
  //$image3 = $image2 + 1;
  //$image4 = $image3 + 1;
  
  if ($pageNumber > 0) {
    echo "<a href=\"gallery.php?page=$prevPage\">";
    echo '<div class="services_arrow services_arrow_left"></div></a>';
  }

  echo '<div class="services">';
  echo "  <div class=\"gallery_img\"><a href=\"gallery/$image1.jpg\"><img src=\"gallery/$image1.jpg\" /></a></div>";
  echo "  <div class=\"gallery_img\"><a href=\"gallery/$image2.jpg\"><img src=\"gallery/$image2.jpg\" /></a></div>";
  echo "  <div class=\"gallery_img\"><a href=\"gallery/$image3.jpg\"><img src=\"gallery/$image3.jpg\" /></a></div>";
  echo "  <div class=\"gallery_img\"><a href=\"gallery/$image4.jpg\"><img src=\"gallery/$image4.jpg\" /></a></div>";
  echo '</div>';
  
  if ($pageNumber < 2) {
    echo "<a href=\"gallery.php?page=$nextPage\">";
    echo '<div class="services_arrow services_arrow_right"></div></a>';
  }
  
  include ('footer.html');
?>
