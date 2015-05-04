<?php
namespace Project\Controllers;

use Ionian\Core\Controller;
use Ionian\Database\Database;

class venueController extends Controller {
    /**
     * Get all available venues.
     */
    public function allAction() {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM venues");
        $stm->execute();
        $res = $stm->fetchAll();
        /*$venueIDs = array();
        $venues = array();
        foreach ($res as $venue) {
            $venueIDs[] = $venue['venue_id'];
            $venues[$venue['venue_id']] = $venue;
            $venues[$venue['venue_id']]['events'] = array();
        }
        $stm = $db->prepare("SELECT * FROM events");
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        $evIds = array();
        foreach ($res as $ev) {
            $evIds[] = $ev['event_id'];
            $venues[$ev['venue_id']]['events'] = array('event_id' => $ev['event_id'],'event_org_id' => $ev['event_org_id'],
                'event_title' => $ev['event_title'],'event_type' => $ev['event_type'], 'event_date' => $ev['event_date'],
                'event_time' => $ev['event_time'], 'event_age_restr' => $ev['event_age_restr'], 'event_popularity' => $ev['event_popularity'],
                'event_ticket_uri' => $ev['event_ticket_uri']);
            $venues[$ev['venue_id']]['events']['artists'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id JOIN venues ON events.venue_id = venues.venue_id
        WHERE event_artists.event_id IN ('.implode(',', $evIds).')');
        $stmt->execute();

        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $event) {
            $venues[$event['venue_id']]['events']['artists'][] = array('artist_id' => $event['artist_id'], 'artist_org_id' => $event['artist_org_id'],
            'artist_name' => $event['artist_name'], 'artist_image' => $event['artist_image']);
        }
        $this->outputJSON("All venues", $venues);*/
        $this->outputJSON("All venues", $res);
    }

    /**
     * Parameter $id must be set. Get all events at the venue with venue id '$id'.
     */
    public function idAction($id) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM venues WHERE venues.venue_id = :id");
        $stm->bindParam(':id', $id, Database::PARAM_INT);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$venueIDs = array();
        $venues = array();
        foreach ($res as $venue) {
            $venueIDs[] = $venue['venue_id'];
            $venues[$venue['venue_id']] = $venue;
            $venues[$venue['venue_id']]['event'] = array();
        }
        $stm = $db->prepare("SELECT * FROM events WHERE events.venue_id = :id");
        $stm->bindParam(':id', $id, Database::PARAM_INT);
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        $evIds = array();
        foreach ($res as $ev) {
            $evIds[] = $ev['event_id'];
            $venues[$ev['venue_id']]['event'] = array('event_id' => $ev['event_id'],'event_org_id' => $ev['event_org_id'],
                'event_title' => $ev['event_title'],'event_type' => $ev['event_type'], 'event_date' => $ev['event_date'],
                'event_time' => $ev['event_time'], 'event_age_restr' => $ev['event_age_restr'], 'event_popularity' => $ev['event_popularity'],
                'event_ticket_uri' => $ev['event_ticket_uri']);
            $venues[$ev['venue_id']]['event']['artists'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id JOIN venues ON events.venue_id = venues.venue_id
        WHERE venues.venue_id = :id');
        $stmt->bindParam(':id', $id, Database::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $event) {
            $venues[$event['venue_id']]['event']['artists'][] = array('artist_id' => $event['artist_id'], 'artist_org_id' => $event['artist_org_id'],
                'artist_name' => $event['artist_name'], 'artist_image' => $event['artist_image']);
        }*/
        $this->outputJSON("All events @ venue-ID $id", $res);
    }

    /**
     * Parameter $name must be set. Get all events at a certain venue, for example 'Tyrol'.
     */
    public function nameAction($name) {
        $db = Database::get();
        $name2 = '%' . $name . '%';
        $stm = $db->prepare("SELECT * FROM venues WHERE venues.venue_name LIKE :name");
        $stm->bindParam(':name', $name2, Database::PARAM_INT);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$venueIDs = array();
        $venues = array();
        foreach ($res as $venue) {
            $venueIDs[] = $venue['venue_id'];
            $venues[$venue['venue_id']] = $venue;
            $venues[$venue['venue_id']]['event'] = array();
        }
        $stm = $db->prepare('SELECT * FROM events WHERE events.venue_id IN ('.implode(',', $venueIDs).')');
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        $evIds = array();
        foreach ($res as $ev) {
            $evIds[] = $ev['event_id'];
            $venues[$ev['venue_id']]['event'] = array('event_id' => $ev['event_id'],'event_org_id' => $ev['event_org_id'],
                'event_title' => $ev['event_title'],'event_type' => $ev['event_type'], 'event_date' => $ev['event_date'],
                'event_time' => $ev['event_time'], 'event_age_restr' => $ev['event_age_restr'], 'event_popularity' => $ev['event_popularity'],
                'event_ticket_uri' => $ev['event_ticket_uri']);
            $venues[$ev['venue_id']]['event']['artists'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id JOIN venues ON events.venue_id = venues.venue_id
        WHERE venues.venue_id IN ('.implode(',', $venueIDs).')');
        $stmt->bindParam(':id', $id, Database::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $event) {
            $venues[$event['venue_id']]['event']['artists'][] = array('artist_id' => $event['artist_id'], 'artist_org_id' => $event['artist_org_id'],
                'artist_name' => $event['artist_name'], 'artist_image' => $event['artist_image']);
        }*/
        $this->outputJSON("All events @ $name", $res);
    }

    /**
     * Parameter $city must be set. Get all events in a certain city, for example 'Stockholm'.
     */
    public function cityAction($city) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM venues WHERE venues.venue_city = :city");
        $stm->bindParam(':city', $city, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$venueIDs = array();
        $venues = array();
        foreach ($res as $venue) {
            $venueIDs[] = $venue['venue_id'];
            $venues[$venue['venue_id']] = $venue;
            $venues[$venue['venue_id']]['event'] = array();
        }
        $stm = $db->prepare('SELECT * FROM events WHERE events.venue_id IN ('.implode(',', $venueIDs).')');
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        $evIds = array();
        foreach ($res as $ev) {
            $evIds[] = $ev['event_id'];
            $venues[$ev['venue_id']]['event'] = array('event_id' => $ev['event_id'],'event_org_id' => $ev['event_org_id'],
                'event_title' => $ev['event_title'],'event_type' => $ev['event_type'], 'event_date' => $ev['event_date'],
                'event_time' => $ev['event_time'], 'event_age_restr' => $ev['event_age_restr'], 'event_popularity' => $ev['event_popularity'],
                'event_ticket_uri' => $ev['event_ticket_uri']);
            $venues[$ev['venue_id']]['event']['artists'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id JOIN venues ON events.venue_id = venues.venue_id
        WHERE venues.venue_id IN ('.implode(',', $venueIDs).')');
        $stmt->bindParam(':id', $id, Database::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $event) {
            $venues[$event['venue_id']]['event']['artists'][] = array('artist_id' => $event['artist_id'], 'artist_org_id' => $event['artist_org_id'],
                'artist_name' => $event['artist_name'], 'artist_image' => $event['artist_image']);
        }*/
        $this->outputJSON("All events in $city", $res);
    }

    /**
     * Parameter $latlng must be set. Get all events in a certain city, for example 'Stockholm'.
     */
    public function latlngAction($latlng) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM venues WHERE venues.venue_latlng = :latlng");
        $stm->bindParam(':latlng', $latlng, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$venueIDs = array();
        $venues = array();
        foreach ($res as $venue) {
            $venueIDs[] = $venue['venue_id'];
            $venues[$venue['venue_id']] = $venue;
            $venues[$venue['venue_id']]['event'] = array();
        }
        $stm = $db->prepare('SELECT * FROM events WHERE events.venue_id IN ('.implode(',', $venueIDs).')');
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        $evIds = array();
        foreach ($res as $ev) {
            $evIds[] = $ev['event_id'];
            $venues[$ev['venue_id']]['event'] = array('event_id' => $ev['event_id'],'event_org_id' => $ev['event_org_id'],
                'event_title' => $ev['event_title'],'event_type' => $ev['event_type'], 'event_date' => $ev['event_date'],
                'event_time' => $ev['event_time'], 'event_age_restr' => $ev['event_age_restr'], 'event_popularity' => $ev['event_popularity'],
                'event_ticket_uri' => $ev['event_ticket_uri']);
            $venues[$ev['venue_id']]['event']['artists'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id JOIN venues ON events.venue_id = venues.venue_id
        WHERE venues.venue_id IN ('.implode(',', $venueIDs).')');
        $stmt->bindParam(':id', $id, Database::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $event) {
            $venues[$event['venue_id']]['event']['artists'][] = array('artist_id' => $event['artist_id'], 'artist_org_id' => $event['artist_org_id'],
                'artist_name' => $event['artist_name'], 'artist_image' => $event['artist_image']);
        }*/
        $this->outputJSON("All events @ $latlng", $res);
    }

    /**
     * Parameter $address must be set. Get all events at specific coordinates.
     */
    public function addressAction($address) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM venues WHERE venues.venue_address = :address");
        $stm->bindParam(':address', $address, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$venueIDs = array();
        $venues = array();
        foreach ($res as $venue) {
            $venueIDs[] = $venue['venue_id'];
            $venues[$venue['venue_id']] = $venue;
            $venues[$venue['venue_id']]['event'] = array();
        }
        $stm = $db->prepare('SELECT * FROM events WHERE events.venue_id IN (' . implode(',', $venueIDs) . ')');
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        $evIds = array();
        foreach ($res as $ev) {
            $evIds[] = $ev['event_id'];
            $venues[$ev['venue_id']]['event'] = array('event_id' => $ev['event_id'], 'event_org_id' => $ev['event_org_id'],
                'event_title' => $ev['event_title'], 'event_type' => $ev['event_type'], 'event_date' => $ev['event_date'],
                'event_time' => $ev['event_time'], 'event_age_restr' => $ev['event_age_restr'], 'event_popularity' => $ev['event_popularity'],
                'event_ticket_uri' => $ev['event_ticket_uri']);
            $venues[$ev['venue_id']]['event']['artists'] = array();
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id JOIN venues ON events.venue_id = venues.venue_id
        WHERE venues.venue_id IN (' . implode(',', $venueIDs) . ')');
        $stmt->bindParam(':id', $id, Database::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $event) {
            $venues[$event['venue_id']]['event']['artists'][] = array('artist_id' => $event['artist_id'], 'artist_org_id' => $event['artist_org_id'],
                'artist_name' => $event['artist_name'], 'artist_image' => $event['artist_image']);
        }*/
        $this->outputJSON("All events @ $address", $res);
    }
}
