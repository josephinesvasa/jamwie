<?php
$data = file_get_contents("http://api.songkick.com/api/3.0/events.json?apikey=4lLW1CkAU1uKMqJl&location=sk:32252&min_date=2015-04-24&max_date=2015-04-25");
$data = json_decode($data, true);
//print_r($data["resultsPage"]["results"]);
$data=$data["resultsPage"]["results"];

for ($i=0; $i < count($data); $i++){
   echo $data[$i];
}
//for($i=0; $i < $data; $i++){
//  echo ["results"]["event"][$i]["type"];
// }

//echo $data["query"]["results"]["quote"]["symbol"];
