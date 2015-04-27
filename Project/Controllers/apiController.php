<?php
namespace Project\Controllers;

use Ionian\Core\Controller;
use Ionian\Database\Database;
use PDO;

class apiController extends Controller {
    public function indexAction(){
        $api=$this->getSongkickApiAction();
        $this->insertDbAction($api);
        //$this->getLastfmApiAction("Carubine");
    }
    public function getSongkickApiAction(){
        $data = file_get_contents("http://api.songkick.com/api/3.0/events.json?apikey=4lLW1CkAU1uKMqJl&location=sk:32252&min_date=2015-04-27&max_date=2015-04-28");
        $data = json_decode($data, true);
        $data=$data["resultsPage"]["results"]["event"];
        return $data;

    }
    public function getLastfmApiAction($artist){
        $data=file_get_contents("http://ws.audioscrobbler.com/2.0/?method=artist.search&artist=$artist&api_key=cab5f651e806648c473644101ac30b33&format=json");
        $data=json_decode($data, true);
        $check_array=$data['results']['artistmatches']['artist'];

        if(array_key_exists(0, $check_array)) {
            $data=$data['results']['artistmatches']['artist'][0]['image'][4]['#text'];

        }
       else {
           $data = $data['results']['artistmatches']['artist']['image'][4]['#text'];
       }

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
        $db=Database::get();
        for ($i=0; $i < count($data); $i++){
            //venue
            $venue_org_id=$data[$i]['venue']['id'];
            $venue_name=$data[$i]['venue']['displayName'];
            $latlng=$data[$i]['venue']['lat'].",".$data[$i]['venue']['lng'];
            if($latlng!==",") {
                $venue_address = $this->getGoogleAddress($latlng);
                $venue_map=$this->getGoogleMap($latlng);
                $venue_map_image=$this->getGoogleMapImage($latlng);
                usleep(500000);
            }
            else{
                 $venue_address='Okänd';
                 $latlng='Okänd';
                 $venue_map='Okänd';
                 $venue_map_image='Okänd';
            }
            $venue_city=$data[$i]['venue']['metroArea']['displayName'];

            $stm=$db->prepare('Insert into venues
                (venue_org_id,venue_name,venue_address,venue_city, venue_latlng, venue_map, venue_map_image)
                values
                (:venue_org_id,:venue_name,:venue_address,:venue_city,:venue_latlng,:venue_map,:venue_map_image)');
            $stm->bindParam(":venue_org_id", $venue_org_id);
            $stm->bindParam(":venue_name", $venue_name);
            $stm->bindParam(":venue_address", $venue_address);
            $stm->bindParam(":venue_city", $venue_city);
            $stm->bindParam(":venue_latlng", $latlng);
            $stm->bindParam(":venue_map", $venue_map);
            $stm->bindParam(":venue_map_image", $venue_map_image);
            if($stm->execute()){
                $last_venue_id=$db->lastInsertId();
                echo $last_venue_id;
            }

            //Event
            $event_org_id=$data[$i]["id"];
            $date=$data[$i]['start']['date'];
            $time = $data[$i]['start']['time'];
            if($time==""){
                $time="Tid ej satt";
            }
            $type=$data[$i]['type'];
            $age_restr=$data[$i]['ageRestriction'];
            if($age_restr==""){
                $age_restr="Ingen åldergräns";
            }

            $title=$data[$i]['displayName'];
            $ticket_uri=$data[$i]['uri'];
            $popularity=$data[$i]['popularity'];

            //artist
            $performance=$data[$i]['performance'];
            for($x=0; $x < count($performance); $x++) {
                $artist_org_id=$performance[$x]['artist']['id'];
                $artist_name = $performance[$x]['artist']['displayName'];
                $artist_name_image = str_replace(' ', '+', $artist_name);
                $artist_image=$this->getLastfmApiAction($artist_name_image);
                if($artist_image!=true){
                    $artist_image="http://thetasa.org/wp-content/uploads/2014/05/thumbnail-default.jpg";
                }

            }

        }
    }
}