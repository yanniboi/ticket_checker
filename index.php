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
foreach ($results as $var => $data) {

  foreach ($data as $show) {
    //print "<h3>Show</h3>";
    foreach ($show->childNodes as $i => $childnode) {
      //var_dump($childnode->tagName);
      //debug_zval_dump($childnode);


      //debug_zval_dump($child);
      if ($childnode->hasChildNodes()) {


        foreach ($childnode->childNodes as $childnodechild) {
          $attributes = $childnodechild->nodeValue;
          if (get_class($childnodechild) !== 'DOMText') {
            if ($childnodechild->tagName == 'img') {
              //print 'img';
              continue;
            }

            if ($childnodechild->tagName == 'p') {
              //print "p";

              /*if ($childnodechild->hasChildNodes()) {
                foreach ($childnodechild->childNodes as $p) {
                  debug_zval_dump($p);
                  print "</br></br></br>";

                }

              }*/
              if ($childnodechild->nodeValue == 'The Now Show'
                || $childnodechild->nodeValue == 'The News Quiz') {
                print "<h4>$childnodechild->nodeValue</h4>";
                continue;
              }
              else {
                continue;
              }

            }


          }
          else {
            if ($childnodechild->nodeValue == 'Apply now') {
              print "<h4>$childnodechild->nodeValue</h4>";
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
  //if (!empty($day)) {
    //$raw[$date] = $day;
 // }
}
