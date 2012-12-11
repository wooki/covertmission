<?php
class Game {
    
    var $slug = '';
    var $name = '';
    var $state = '';
    var $admin_name = '';
    
    // load from disc, from json file
    static function load($slug, $ci) {
        $ci->load->helper('file');
        $json_data = $ci->file->read_file('./data/game-'.$slug.'.json');
        if ($json_data != false) {
            return json_decode($json_data);
        } else {
            return false;
        }
    }

    // save to disc, as json
    static function save($game, $ci) {
        $ci->load->helper('file');
        $json_data = json_encode($game);
        return $ci->file->write_file('./data/game-'.$game->slug.'.json', $json_data);
    }

    // create a new game
    static function create($name) {
        $g = new Game();
        $g->name = $name;
        $g->slug = Game::generate_slug($g->name);
        $g->state = "joining";        
        return $g;
    }
    
    // generate a slug for the game
    static function generate_slug($name) {
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
        return $slug;        
    }

}