<?php

require ('header.php');

  $about = file_get_contents('about.txt');
  $about = explode("\n", $about);
  foreach ($about as $line)
    if ($line != '')
      echo "          <p>$line</p>\n";


echo '        <table width="100%"><tr>
          <td width="50%">
            <p><strong>Location</strong></p>
            <p>Tay\'s Nail & Spa</p>
';

  $address = file_get_contents('address.txt');
  $address = explode("\n", $address);
  foreach ($address as $line)
    if ($line != '')
      echo "            <p>$line</p>\n";

  echo '
            <p><strong>Hours</strong></p>
';
  $hours = file_get_contents('hours.txt');
  $hours = explode("\n", $hours);
  foreach ($hours as $line)
    if ($line != '')
      echo "            <p>$line</p>\n";

  $googleMap = scandir('google_map');
  $googleMap = $googleMap[2];
  $googleMapLink = file_get_contents('gmaplink.txt');
  $googleMapLink = explode("\n", $googleMapLink);
  $googleMapLink = $googleMapLink[0];

echo '          </td>
          <td width="50%">
            <a href="'.$googleMapLink.'">
              <p class="blueline"><img src="/google_map/'.$googleMap.'" /></p>
              <p class="blueline">Click here for Google Maps</p>
            </a>
          </td></tr></table>
          <p class="blueline">Make an appointment via phone or via online (coming soon)</p>';

include ('footer.html');

?>
