<?php
class Game_List {
    
    var $games = array();
    var $games_list_file_path = './data/games.json';
    
    // load from disc, from json file
    function load() {
        
        // check for file - and create an empty file if one doesn't exist
        $json_data = read_file($this->games_list_file_path);
        if ($json_data != false) {
            $this->games = json_decode($json_data);
        }
    }

    // save to disc, as json
    function save() {
        $json_data = json_encode($this->games);
        write_file($this->games_list_file_path, $json_data);
    }    
    
    function update_game($name, $slug, $state) {
        $updated = false;
        foreach ($this->games as &$game) {
            if ($slug == $game->slug) {
                $updated = true;
                $game->state = $state;
            }
        }
        if ($updated == false) {
            $this->games[] = array(
              'name' => $name,
              'slug' => $slug,
              'state' => $state              
            );
        }
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