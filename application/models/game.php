<?php
class Game {
    
    var $slug = '';
    var $name = '';
    var $state = '';
    var $admin_name = '';
    
    // load from disc, from json file
    function static load($slug) {
        $ci =& get_instance();
        $ci->load->helper('file');
        $json_data = $ci->file->read_file('./data/game-'.$slug.'.json');
        if ($json_data != false) {
            return json_decode($json_data);
        } else {
            return false;
        }
    }

    // save to disc, as json
    function static save($game) {
        $ci =& get_instance();
        $ci->load->helper('file');
        $json_data = json_encode($game);
        return $ci->file->write_file('./data/game-'.$game->slug.'.json', $json_data);
    }

    // create a new game
    function static create($name) {
        $g = new Game();
        $g->name = $name;
        $g->slug = Game::generate_slug($g->name);
        $g->state = "joining";        
    }
    
    // generate a slug for the game
    function static generate_slug($name) {
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
        return $slug;        
    }

}