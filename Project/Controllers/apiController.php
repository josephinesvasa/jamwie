<?php
namespace Project\Controllers;
use Ionian\Core\Controller;
use Ionian\Database\Database;
use PDO;
class apiController extends Controller
{
    public function indexAction()
    {
        $api = $this->getSongkickApiAction();
        $this->insertDbAction($api);
        //$this->getLastfmApiAction("Carubine");
    }

    public function getSongkickApiAction()
    {
        $data = file_get_contents("http://api.songkick.com/api/3.0/events.json?apikey=4lLW1CkAU1uKMqJl&location=sk:32252&min_date=2015-04-29&max_date=2015-04-30");
        $data = json_decode($data, true);
        $data = $data["resultsPage"]["results"]["event"];
        return $data;
    }

    public function getLastfmApiAction($artist)
    {
        $data = file_get_contents("http://ws.audioscrobbler.com/2.0/?method=artist.search&artist=$artist&api_key=cab5f651e806648c473644101ac30b33&format=json");
        $data = json_decode($data, true);
        $check_array = $data['results']['artistmatches']['artist'];
        if (array_key_exists(0, $check_array)) {
            $data = $data['results']['artistmatches']['artist'][0]['image'][4]['#text'];
        } else {
            $data = $data['results']['artistmatches']['artist']['image'][4]['#text'];
        }
        return $data;
    }

    public function getGoogleAddress($latlng)
    {
        //https://www.google.com/maps/place/59%C2%B020'13.5%22N+18%C2%B003'33.0%22E/@59.337091,18.0591612,15z/data=!4m2!3m1!1s0x0:0x0
        //$latlng = "59.3438583,18.0559941";
        $data = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$latlng");
        $data = json_decode($data, true);
        $result = $data["results"][0]['formatted_address'];
        //$data=$data["resultsPage"]["results"]["event"];
        return $result;
    }

    public function getGoogleMap($latlng)
    {
        //$latlng = "59.3438583,18.0559941";
        $map = "https://www.google.com/maps/place/$latlng";
        return $map;
    }

    public function getGoogleMapImage($latlng)
    {
        //$latlng = "59.3438583,18.0559941";
        $image = "http://maps.googleapis.com/maps/api/staticmap?center=$latlng&zoom=15&size=620x260&sensor=false&markers=color%3A0xf80046%7C$latlng";
        return $image;
    }

    function backupDelete() {
        $db = Database::get();
        $length = 5;
        $available = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chlength = strlen($available);
        $random = '';
        for ($x = 0; $x < $length; $x++) {
            $random .= $available[rand(0, $chlength - 1)];
        }
        $dateToday=date("Y-m-d");
        $eventFilename = 'events-'.$dateToday.'-'.$random.'-'.'.csv';
        $sql = $db->prepare("SELECT * FROM events INTO OUTFILE '$eventFilename' FIELDS ENCLOSED BY '\"' TERMINATED BY '\;' ESCAPED BY '\"'");
        if ($sql->execute()) {
            $stm = $db->prepare("DELETE FROM events");
            $stm->execute();
        }
        $eventArtistFilename = 'eventartists-'.$dateToday.'-'.$random.'-'.'.csv';
        $sql2 = $db->prepare("SELECT * FROM event_artists INTO OUTFILE '$eventArtistFilename' FIELDS ENCLOSED BY '\"' TERMINATED BY '\;' ESCAPED BY '\"'");
        if ($sql2->execute()) {
            $stm = $db->prepare("DELETE FROM event_artists");
            $stm->execute();
        }
    }

    public function insertDbAction($data)
    {
        $db = Database::get();
        for ($i = 0; $i < count($data); $i++) {
            //venue
            $venue_org_id = $data[$i]['venue']['id'];
            $venue_name = $data[$i]['venue']['displayName'];
            $latlng = $data[$i]['venue']['lat'] . "," . $data[$i]['venue']['lng'];
            if ($latlng !== ",") {
                $venue_address = $this->getGoogleAddress($latlng);
                $venue_map = $this->getGoogleMap($latlng);
                $venue_map_image = $this->getGoogleMapImage($latlng);
                usleep(500000);
            } else {
                $venue_address = 'Okänd';
                $latlng = 'Okänd';
                $venue_map = 'Okänd';
                $venue_map_image = 'Okänd';
            }
            $venue_city = $data[$i]['venue']['metroArea']['displayName'];
            $stm_check_venue = $db->prepare("select venue_org_id, venue_id from venues where venue_org_id=:venue_org_id");
            $stm_check_venue->bindParam(":venue_org_id", $venue_org_id);
            if ($stm_check_venue->execute()) {
                if ($stm_check_venue->rowCount() < 1) {
                    $stm_insert_venue = $db->prepare('Insert into venues
                    (venue_org_id,venue_name,venue_address,venue_city, venue_latlng, venue_map, venue_map_image)
                    values
                    (:venue_org_id,:venue_name,:venue_address,:venue_city,:venue_latlng,:venue_map,:venue_map_image)');
                    $stm_insert_venue->bindParam(":venue_org_id", $venue_org_id);
                    $stm_insert_venue->bindParam(":venue_name", $venue_name);
                    $stm_insert_venue->bindParam(":venue_address", $venue_address);
                    $stm_insert_venue->bindParam(":venue_city", $venue_city);
                    $stm_insert_venue->bindParam(":venue_latlng", $latlng);
                    $stm_insert_venue->bindParam(":venue_map", $venue_map);
                    $stm_insert_venue->bindParam(":venue_map_image", $venue_map_image);
                    if ($stm_insert_venue->execute()) {
                        $last_venue_id = $db->lastInsertId();
                    }
                } else {
                    $result = $stm_check_venue->fetch();
                    $last_venue_id = $result['venue_id'];
                }
            }
            //Event
            $event_org_id = $data[$i]["id"];
            $event_date = $data[$i]['start']['date'];
            $event_time = $data[$i]['start']['time'];
            if ($event_time == "") {
                $event_time = "Tid ej satt";
            }
            $event_type = $data[$i]['type'];
            $event_age_restr = $data[$i]['ageRestriction'];
            if ($event_age_restr == "") {
                $event_age_restr = "Ingen åldergräns";
            }
            $event_title = $data[$i]['displayName'];
            $event_ticket_uri = $data[$i]['uri'];
            $event_popularity = $data[$i]['popularity'];
            $stm_insert_event = $db->prepare("insert into events
                   (event_org_id, event_date, event_time, event_type, event_age_restr, event_title, event_ticket_uri, event_popularity, venue_id)
                    VALUES
                    (:event_org_id, :event_date, :event_time, :event_type, :event_age_restr, :event_title, :event_ticket_uri, :event_popularity, :venue_id)");
            $stm_insert_event->bindParam(":event_org_id", $event_org_id);
            $stm_insert_event->bindParam(":event_date", $event_date);
            $stm_insert_event->bindParam(":event_time", $event_time);
            $stm_insert_event->bindParam(":event_type", $event_type);
            $stm_insert_event->bindParam(":event_age_restr", $event_age_restr);
            $stm_insert_event->bindParam(":event_title", $event_title);
            $stm_insert_event->bindParam(":event_ticket_uri", $event_ticket_uri);
            $stm_insert_event->bindParam(":event_popularity", $event_popularity);
            $stm_insert_event->bindParam(":venue_id", $last_venue_id);
            if ($stm_insert_event->execute()) {
                $last_event_id = $db->lastInsertId();
            }
            //artist
            $performance = $data[$i]['performance'];
            for ($x = 0; $x < count($performance); $x++) {
                $artist_org_id = $performance[$x]['artist']['id'];
                echo $artist_org_id;
                $artist_name = $performance[$x]['artist']['displayName'];
                $artist_name_image = str_replace(' ', '+', $artist_name);
                $artist_image = $this->getLastfmApiAction($artist_name_image);
                if ($artist_image != true) {
                    $artist_image = "http://thetasa.org/wp-content/uploads/2014/05/thumbnail-default.jpg";
                }
                $stm_check_artist = $db->prepare("select artist_org_id, artist_id from artists where artist_org_id=:artist_org_id");
                $stm_check_artist->bindParam(":artist_org_id", $artist_org_id);
                if ($stm_check_artist->execute()) {
                    if ($stm_check_artist->rowCount() < 1) {
                        $stm_insert_artist = $db->prepare("insert into artists
                            (artist_org_id, artist_name, artist_image)
                            VALUES
                            (:artist_org_id, :artist_name, :artist_image)");
                        $stm_insert_artist->bindParam(":artist_org_id", $artist_org_id);
                        $stm_insert_artist->bindParam(":artist_name", $artist_name);
                        $stm_insert_artist->bindParam(":artist_image", $artist_image);
                        if ($stm_insert_artist->execute()) {
                            $last_artist_id = $db->lastInsertId();
                        }
                    } else {
                        $result_artist = $stm_check_artist->fetch();
                        $last_artist_id = $result_artist['artist_id'];
                    }
                    $stm_connect_event_artist = $db->prepare("insert into event_artists
                            (event_id, artist_id)
                            VALUES
                            (:event_id, :artist_id)");
                    $stm_connect_event_artist->bindParam(":event_id", $last_event_id);
                    $stm_connect_event_artist->bindParam(":artist_id", $last_artist_id);
                    if ($stm_connect_event_artist->execute()) {
                        echo "ok";
                    }
                }
            }
        }
    }
}