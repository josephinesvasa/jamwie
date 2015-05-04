<?php
namespace Project\Controllers;

use Ionian\Core\Controller;
use Ionian\Database\Database;

/*
 * Use the URL bar to search the API.
 * For example, http://localhost/jamwie/event/id/EVENT-ID gives info about an event with a specific id.
 */

class eventController extends Controller {

    /**
     * jamwie/event/all generates all events and the performing artists.
     */
    public function allAction() {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id");
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
            'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $id must be set. Get info about a specific event.
     */
    public function idAction($id) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_id = :id");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_id = :id");
        $stm->bindParam(':id', $id, Database::PARAM_INT);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $type must be set. Get all events for a certain type, for example 'Concert'.
     */
    public function typeAction($type) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_type = :type");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_type = :type");
        $stm->bindParam(':type', $type, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $date must be set. Get all events on a certain date. Must be written in the 'YYYY-MM-DD' format.
     */
    public function dateAction($date) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_date = :date");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_date = :date");
        $stm->bindParam(':date', $date, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $time must be set. Get all events at a certain time. Must be written in the 'HHHH-MM-SS' format.
     */
    public function timeAction($time) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_time = :time");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_time = :time");
        $stm->bindParam(':time', $time, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $agerest must be set. Get all events with a certain age restriction, for example 'Ingen'.
     */
    public function ageRestrictionAction($agerest) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_age_restr = :agerestr");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_age_restr = :agerestr");
        $stm->bindParam(':agerestr', $agerest, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $title must be set. Get all events with a certain title.
     */
    public function titleAction($title) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_title LIKE :title");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_title LIKE :title");
        $stm->bindParam(':title', $title, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll();
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }

    /**
     * Parameter $turl must be set. Get all events with a certain ticket URL.
     */
    public function ticketURLAction($turl) {
        $db = Database::get();
        $stm = $db->prepare("SELECT * FROM events WHERE events.event_ticket_uri LIKE :ticket");
        // $stm = $db->prepare("SELECT * FROM events JOIN venues ON events.venue_id = venues.venue_id WHERE events.event_ticket_uri LIKE :ticket");
        $stm->bindParam(':ticket', $turl, Database::PARAM_STR);
        $stm->execute();
        $res = $stm->fetchAll(Database::FETCH_ASSOC);
        /*$eventIDs = array();
        $events = array();
        foreach ($res as $event) {
            $eventIDs[] = (int)$event['event_id'];
            $events[$event['event_id']] = $event;
        }
        $stmt = $db->prepare('SELECT * FROM event_artists JOIN artists ON event_artists.artist_id = artists.artist_id
        JOIN events ON event_artists.event_id = events.event_id WHERE event_artists.event_id IN ('.implode(',', $eventIDs).')');
        $stmt->execute();
        $results = $stmt->fetchAll(Database::FETCH_ASSOC);
        foreach ($results as $result) {
            $events[$result['event_id']]['artists'][] = array('artist_id' => $result['artist_id'], 'artist_org_id' => $result['artist_org_id'],
                'artist_name' => $result['artist_name'], 'artist_image' => $result['artist_image']);
        }*/
        $this->outputJSON("All events", $res);
    }
}
