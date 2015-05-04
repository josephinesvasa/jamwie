<?php
namespace Project\Controllers;

use Ionian\Core\Controller;
use Ionian\Database\Database;


/*
 * Use the URL bar to search the API.
 * For example, http://localhost/jamwie/artist/name/ARTISTNAME gives info about the specific artist.
 */


class artistController extends Controller {

    /**
     * jamwie/artist/all generates all artists and the events they have planned.
     */
   public function allAction() {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM artists");
        $stm->execute();
        $res = $stm->fetchAll();
        /*$artistIDs = array();
        $artists = array();
        foreach ($res as $artist) {
            $artistIDs[] = (int)$artist['artist_id'];
            $artists[$artist['artist_id']] = $artist;
            $artists[$artist['artist_id']]['events'] = array();

        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN events ON event_artists.event_id = events.event_id
        JOIN venues ON events.venue_id = venues.venue_id WHERE event_artists.artist_id IN ('.implode(',', $artistIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $result) {
            $artists[$result['artist_id']]['events'][] = array('event_id' => $result['event_id'],'event_org_id' => $result['event_org_id'],
                'event_title' => $result['event_title'],'event_type' => $result['event_type'], 'event_date' => $result['event_date'],
                'event_time' => $result['event_time'], 'event_age_restr' => $result['event_age_restr'], 'event_popularity' => $result['event_popularity'],
                'event_ticket_uri' => $result['event_ticket_uri'], 'venue_id' => $result['venue_id'], 'venue_org_id' => $result['venue_org_id'],
                'venue_name' => $result['venue_name'], 'venue_address' => $result['venue_address'], 'venue_city' => $result['venue_city'],
                'venue_latlng' => $result['venue_latlng'], 'venue_map' => $result['venue_map'],'venue_map_image' => $result['venue_map_image'],);
        }*/
        $this->outputJSON("All artists and their events", $res);
    }


    /**
     * jamwie/artist/name/$name
     * Parameter $name must be set. Get all upcoming events for the artist '$name'.
     */
    public function nameAction($name) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM artists WHERE `artist_name` = :name");
        $stm->bindParam(':name', $name, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$artistIDs = array();
        $artists = array();
        foreach ($res as $artist) {
            $artistIDs[] = (int)$artist['artist_id'];
            $artists[$artist['artist_id']] = $artist;
            $artists[$artist['artist_id']]['events'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN events ON event_artists.event_id = events.event_id
        JOIN venues ON events.venue_id = venues.venue_id WHERE event_artists.artist_id IN ('.implode(',', $artistIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $result) {
            $artists[$result['artist_id']]['events'][] = array('event_id' => $result['event_id'],'event_org_id' => $result['event_org_id'],
                'event_title' => $result['event_title'],'event_type' => $result['event_type'], 'event_date' => $result['event_date'],
                'event_time' => $result['event_time'], 'event_age_restr' => $result['event_age_restr'], 'event_popularity' => $result['event_popularity'],
                'event_ticket_uri' => $result['event_ticket_uri'], 'venue_id' => $result['venue_id'], 'venue_org_id' => $result['venue_org_id'],
                'venue_name' => $result['venue_name'], 'venue_address' => $result['venue_address'], 'venue_city' => $result['venue_city'],
                'venue_latlng' => $result['venue_latlng'], 'venue_map' => $result['venue_map'],'venue_map_image' => $result['venue_map_image'],);
        }*/
        $this->outputJSON("All available for $name", $res);
    }

    /**
     * jamwie/artist/id/$id
     * Parameter $id must be set. Get all upcoming events for the artist ID '$id'.
     */
    public function idAction($id) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM artists WHERE artist_id = :id");
        $stm->bindParam(':id', $id, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$artistIDs = array();
        $artists = array();
        foreach ($res as $artist) {
            $artistIDs[] = (int)$artist['artist_id'];
            $artists[$artist['artist_id']] = $artist;
            $artists[$artist['artist_id']]['events'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN events ON event_artists.event_id = events.event_id
        JOIN venues ON events.venue_id = venues.venue_id WHERE event_artists.artist_id IN ('.implode(',', $artistIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $result) {
            $artists[$result['artist_id']]['events'][] = array('event_id' => $result['event_id'],'event_org_id' => $result['event_org_id'],
                'event_title' => $result['event_title'],'event_type' => $result['event_type'], 'event_date' => $result['event_date'],
                'event_time' => $result['event_time'], 'event_age_restr' => $result['event_age_restr'], 'event_popularity' => $result['event_popularity'],
                'event_ticket_uri' => $result['event_ticket_uri'], 'venue_id' => $result['venue_id'], 'venue_org_id' => $result['venue_org_id'],
                'venue_name' => $result['venue_name'], 'venue_address' => $result['venue_address'], 'venue_city' => $result['venue_city'],
                'venue_latlng' => $result['venue_latlng'], 'venue_map' => $result['venue_map'],'venue_map_image' => $result['venue_map_image'],);
        }*/
        $this->outputJSON("All available for artist ID $id", $res);
    }
    /**
     * jamwie/artist/orgid/$id
     * Parameter $id must be set. Get all upcoming events for the Songkick artist ID '$id'.
     */
    public function orgidAction($id) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM artists WHERE artist_org_id = :id");
        $stm->bindParam(':id', $id, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$artistIDs = array();
        $artists = array();
        foreach ($res as $artist) {
            $artistIDs[] = (int)$artist['artist_id'];
            $artists[$artist['artist_id']] = $artist;
            $artists[$artist['artist_id']]['events'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN events ON event_artists.event_id = events.event_id
        JOIN venues ON events.venue_id = venues.venue_id WHERE event_artists.artist_id IN ('.implode(',', $artistIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $result) {
            $artists[$result['artist_id']]['events'][] = array('event_id' => $result['event_id'],'event_org_id' => $result['event_org_id'],
                'event_title' => $result['event_title'],'event_type' => $result['event_type'], 'event_date' => $result['event_date'],
                'event_time' => $result['event_time'], 'event_age_restr' => $result['event_age_restr'], 'event_popularity' => $result['event_popularity'],
                'event_ticket_uri' => $result['event_ticket_uri'], 'venue_id' => $result['venue_id'], 'venue_org_id' => $result['venue_org_id'],
                'venue_name' => $result['venue_name'], 'venue_address' => $result['venue_address'], 'venue_city' => $result['venue_city'],
                'venue_latlng' => $result['venue_latlng'], 'venue_map' => $result['venue_map'],'venue_map_image' => $result['venue_map_image'],);
        }*/
        $this->outputJSON("All available for Songkick artist ID $id", $res);
    }
}