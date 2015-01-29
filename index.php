<?php

include_once('vendor/autoload.php');

use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'http://www.bbc.co.uk/showsandtours/tickets/radio/');
//var_dump($crawler);

$domSelector = '.calendarBody li';
$results = $crawler->filter($domSelector)->each(function ($node) {
  return $node;
});


$raw = array();
$available = array();
$names = array();
$want = array();
$i = 0;

foreach ($results as $var => $data) {
  foreach ($data as $show) {
    $i++;
    //print $i;
    print "</br><hr></br>";
    print "<h3>Show</h3>";
    foreach ($show->childNodes as $childnode) {

      //debug_zval_dump($child);
      if ($childnode->hasChildNodes()) {


        foreach ($childnode->childNodes as $k => $childnodechild) {
          //print $k . "</br>";

          $attributes = $childnodechild->nodeValue;
          if (get_class($childnodechild) !== 'DOMText') {
            if ($childnodechild->tagName == 'p') {

              //print "p";

              if ($childnodechild->hasChildNodes()) {
                foreach ($childnodechild->childNodes as $j => $p) {
                  //print $j;

                  if ($j == 0 && $k == 1) {
                    print '<strong>' . $childnodechild->nodeValue . "</strong></br>";
                    //debug_zval_dump($childnodechild);


                    $names[$i] = $childnodechild->nodeValue;
                    continue;
                  }


                }

              }
              if ($childnodechild->nodeValue == 'The Now Show'
                || $childnodechild->nodeValue == 'The News Quiz') {
                //print "<h4>$childnodechild->nodeValue</h4>";
                $want[$i] = $i;
                continue;
              }
              else {
                continue;
              }

            }

            if ($childnodechild->tagName == 'img') {
              print "<img src=\"" . $childnodechild->getAttribute('src') . "\" /></br>";

              //print 'img';
              continue;
            }


          }
          else {
            if ($childnodechild->nodeValue == 'Apply now') {
              print "<h4>$childnodechild->nodeValue</h4>";
              $available[$i] = $i;
              continue;
            }
            else {
              continue;
            }
          }
        }
      }
      else {
        continue;
      }
    }
  }

}

var_dump($available);
var_dump($names);
var_dump($want);

$email = array_intersect($available, $want);

if (!empty($email)) {
  $to      = 'yan@yanniboi.com';
  $subject = 'Get BBC Tickets';
  $message = 'empty Tickets are available, go now: http://www.bbc.co.uk/showsandtours/tickets/radio/';
  $headers = 'From: admin@yanniboi.com' . "\r\n" .
    'Reply-To: admin@yanniboi.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

  mail($to, $subject, $message, $headers);
}
