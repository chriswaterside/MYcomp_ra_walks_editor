<?php

/**
 * Description of Loader
 *
 * @author Chris Vaughan
 */

namespace Ramblers\Component\Ra_walks_editor\Site;

class Loader extends \RLeafletMap {

    public $fields = [];

    public function __construct() {
        parent::__construct();
    }

    public function editWalk($walkdate) {

        //  $this->help_page = "https://maphelp.ramblers-webs.org.uk/draw-walking-route.html";

        $this->options->settings = true;
        $this->options->mylocation = true;
        $this->options->rightclick = true;
        $this->options->fullscreen = true;
        $this->options->mouseposition = true;
        $this->options->postcodes = true;
        $this->options->fitbounds = true;
        $this->options->displayElevation = false;
        $this->options->cluster = false;
        $this->options->draw = false;
        $this->options->print = true;
        $this->options->ramblersPlaces = true;
        $this->options->controlcontainer = true;
        $this->data = new class {
            
        };
        $this->data->walkdate = $walkdate;
        $this->data->fields = $this->fields;

        parent::setCommand('ra.walkseditor.editwalk');
        parent::setDataObject($this->data);
        parent::display();
        \RWalkseditor::addScriptsandCss();
    }

    public function viewWalk() {
// is this used?
        $this->options->settings = true;
        $this->options->mylocation = true;
        $this->options->rightclick = true;
        $this->options->fullscreen = true;
        $this->options->mouseposition = true;
        $this->options->postcodes = true;
        $this->options->fitbounds = true;
        $this->options->displayElevation = false;
        $this->options->cluster = false;
        $this->options->draw = false;
        $this->options->print = true;
        $this->options->ramblersPlaces = true;
        $this->options->controlcontainer = true;
        $this->data = new class {
            
        };
        $this->data->fields = $this->fields;
        parent::setCommand('ra.walkseditor.viewwalk');
        parent::setDataObject($this->data);
        parent::display();
        \RWalkseditor::addScriptsandCss();
    }

    // view all walks
    public function viewWalks($data) {

        $this->options->settings = true;
        $this->options->mylocation = true;
        $this->options->rightclick = true;
        $this->options->fullscreen = true;
        $this->options->mouseposition = true;
        $this->options->postcodes = true;
        $this->options->fitbounds = true;
        $this->options->displayElevation = false;
        $this->options->cluster = false;
        $this->options->draw = false;
        $this->options->print = true;
        $this->options->ramblersPlaces = true;
        $this->options->controlcontainer = true;
        $this->options->calendar = true;
        $this->data = $data;
        parent::setCommand('ra.walkseditor.comp.viewAllwalks');
        parent::setDataObject($this->data);
        parent::display();
        \RWalkseditor::addScriptsandCss();
    }

    public function editPlace() {
        //  $this->help_page = "https://maphelp.ramblers-webs.org.uk/draw-walking-route.html";

        $this->options->fullscreen = true;
        $this->options->mouseposition = true;
        $this->options->postcodes = true;
        $this->options->fitbounds = false;
        $this->options->displayElevation = false;
        $this->options->cluster = false;
        $this->options->draw = false;
        $this->options->print = true;
        $this->options->ramblersPlaces = true;
        $this->options->controlcontainer = true;

        $this->data = new class {
            
        };
        $this->data->fields = $this->fields;
        parent::setCommand('ra.walkseditor.editPlace');
        parent::setDataObject($this->data);
        parent::display();
        \RWalkseditor::addScriptsandCss();
    }

    public function viewPlaces($data) {
        //  $this->help_page = "https://maphelp.ramblers-webs.org.uk/draw-walking-route.html";

        $this->options->fullscreen = true;
        $this->options->mouseposition = true;
        $this->options->postcodes = true;
        $this->options->fitbounds = false;
        $this->options->displayElevation = false;
        $this->options->cluster = false;
        $this->options->draw = false;
        $this->options->print = true;
        $this->options->ramblersPlaces = true;
        $this->options->controlcontainer = true;

        $this->data = $data;
        $this->data->fields = $this->fields;

        \RLoad::addScript("media/lib_ramblers/walkseditor/js/comp/places.js");

        parent::setCommand('ra.walkseditor.comp.viewAllPlaces');
        parent::setDataObject($this->data);
        parent::display();
        \RWalkseditor::addScriptsandCss();
    }

}
