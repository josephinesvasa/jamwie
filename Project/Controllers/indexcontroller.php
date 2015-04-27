<?php
namespace Project\Controllers;

use Ionian\Core\Controller;

class indexController extends Controller{
    public function indexAction(){
        echo 'get all events';
        echo "</br>";
        echo $this->getDate();
        echo "</br>";
        echo $this->getTime();

    }

}