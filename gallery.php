<?php
  define('LOAD_SCRIPTS', true);

  require ('header.php');

  echo '          <div class="flexslider" id="slider"><ul class="slides">
';

  $images = scandir('gallery');
  unset($images[0]);
  unset($images[1]);

  foreach ($images as $image) {
    echo "            <li><a href=\"/gallery/$image\"><img src=\"/gallery/$image\"></a></li>\n";
  }

  echo '            </ul></div>
            <div class="flexslider" id="carousel"><ul class="slides">
';

  foreach ($images as $image) {
    echo "            <li><img src=\"/gallery_thumb/$image\"></li>\n";
  }

  echo '            </ul></div>
            <script>
              $(\'#carousel\').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 210,
                itemMargin: 5,
                asNavFor: \'#slider\'
              });
              $(\'#slider\').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                sync: "#carousel"
              });
            </script>';
  
  include ('footer.html');
?>
