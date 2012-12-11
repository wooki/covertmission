<?php
class GameList {
    
    var $games = array();
    var $games_list_file_path = './data/games.json';
    
    // load from disc, from json file
    function load() {
        
        // check for file - and create an empty file if one doesn't exist
        $ci =& get_instance();
        $ci->load->helper('file');
        $json_data = $ci->file->read_file($games_list_file_path);
        if ($json_data != false) {
            $this->games = json_decode($json_data);
        }
    }

    // save to disc, as json
    function save() {
        $ci =& get_instance();
        $ci->load->helper('file');
        $json_data = json_encode($this->games);
        $ci->file->write_file($games_list_file_path, $json_data);
    }    
    
    
    // check if the specified item exists in the collection
    function game_exists($name, $slug) {
        $exists = false;
        foreach ($this->games as $game) {
            if ($name == $game->name ||
                $slug == $game->slug) {
                
                return true;
            }
        }
        return $exists;
    }

}