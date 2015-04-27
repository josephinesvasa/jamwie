<?php
namespace Project\Controllers;

use Ionian\Core\Controller;
use Ionian\Database\Database;

class apiController extends Controller {
    public function indexAction(){
        $api=$this->getSongkickApiAction();
        $this->insertDbAction($api);

    }
    public function getSongkickApiAction(){
        $data = file_get_contents("http://api.songkick.com/api/3.0/events.json?apikey=4lLW1CkAU1uKMqJl&location=sk:32252&min_date=2015-04-24&max_date=2015-04-25");
        $data = json_decode($data, true);
        $data=$data["resultsPage"]["results"]["event"];
        return $data;

    }
    public function getGoogleAddress($latlng){
        //https://www.google.com/maps/place/59%C2%B020'13.5%22N+18%C2%B003'33.0%22E/@59.337091,18.0591612,15z/data=!4m2!3m1!1s0x0:0x0
       //$latlng = "59.3438583,18.0559941";
        $data = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng");
        $data = json_decode($data,true);

        $result=$data["results"][0]['formatted_address'];
        //$data=$data["resultsPage"]["results"]["event"];
        return $result;

    }
    public function getGoogleMap($latlng){
        //$latlng = "59.3438583,18.0559941";
        $map="https://www.google.com/maps/place/$latlng";
        return $map;
    }
    public function getGoogleMapImage($latlng){
        //$latlng = "59.3438583,18.0559941";
        $image="http://maps.googleapis.com/maps/api/staticmap?center=$latlng&zoom=15&size=620x260&sensor=false&markers=color%3A0xf80046%7C$latlng";
        return $image;
    }


    public function insertDbAction($data){
        for ($i=0; $i < count($data); $i++){
            $venue_org_id=$data[$i]['venue']['id'];
            $venue_name=$data[$i]['venue']['displayName'];
            $latlng=$data[$i]['venue']['lat'].",".$data[$i]['venue']['lng'];
            if($latlng!==",") {
                $address = $this->getGoogleAddress($latlng);
                echo $address . "</br>";
                usleep(500000);
            }
            else{
                echo $address='Okänd'."</br>";
            }
            $event_org_id=$data[$i]["id"];
            $date=$data[$i]['start']['date'];
            $time=$data[$i]['start']['time'];
            $age_restr=$data[$i]['ageRestriction'];
            $title=$data[$i]['displayName'];
            $ticket_uri=$data[$i]['uri'];


        }
    }
}