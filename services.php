<?php
  define('LOAD_SCRIPTS', true);
  
  require ('header.php');
  
  echo '          <div class="flexslider">
              <ul class="slides">
';
  
  $serviceFiles = scandir('services/');
  unset($serviceFiles[0]);
  unset($serviceFiles[1]);
  
  foreach ($serviceFiles as $serviceFile) {
    echo '                <li><img src="/services/'.$serviceFile.'" /></li>'."\n";
  }
  
  echo '              </ul>
            </div>
            <script>
              $(\'.flexslider\').flexslider({
                animation: "slide"
              });
            </script>
          </div>';
  
  /*$pageNumber = $_GET['page'];
  
  // replace this with MySQL row count et cetera..
  if ($pageNumber != '0' && $pageNumber != '1' && $pageNumber != '2') {
    $pageNumber = 0;
  }
  
  $nextPage = $pageNumber + 1;
  $prevPage = $pageNumber - 1;
  
  if ($pageNumber > 0) {
    echo "          <a href=\"services.php?page=$prevPage\">
";
    echo '            <div class="services_arrow services_arrow_left"></div>
          </a>
';
  }

  $serviceFiles = scandir('services');

  $serviceFile = $serviceFiles[$pageNumber + 2];

  echo '          <div class="services">
';
  echo "            <a href=\"/services/$serviceFile\">
              <img src=\"services/$serviceFile\" />
            </a>";
  echo '
          </div>
';
  
  if (isset($serviceFiles[$pageNumber + 3])) {
    echo "          <a href=\"services.php?page=$nextPage\">
";
    echo '            <div class="services_arrow services_arrow_right"></div>
          </a>';
  }*/
  
  include ('footer.html'); 
?>
