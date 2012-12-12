<?php
class Game {
    
    var $slug = '';
    var $name = '';
    var $state = '';
    var $admin_name = '';
    var $players = array();
    
    // work out the correct url for viewing this game
    static function get_url($game) {
        if ($game->state == 'joining') {
            return '/games/lobby/'.$game->slug; 
        } else {
            return false;
        }    
    }
    
    // load from disc, from json file
    static function load($slug, $game_list) {
        return $game_list->get_game($slug);        
    }

    // save to disc, as json
    static function save($game, $game_list) {
        return $game_list->update_game($game);
    }

    // create a new game
    static function create($name, $admin_name) {
        $g = new Game();
        $g->name = $name;
        $g->admin_name = $admin_name;
        $g->slug = Game::generate_slug($g->name);
        $g->state = "joining";        
        return $g;
    }
    
    // gets the player data for the specified guid, returns false if not found
    static function get_player($game, $guid) {
        foreach ($game->players as $player) {
            if ($player->guid == $guid) {
                return $player;
            }
        }
        return false;
    }
    
    // add a player to the game, returning a random guid for mapping
    // that players requests to this player in the game
    static function add_player($game, $name) {
        $name_slug = Game::generate_slug($name);
        $ok = true;
        foreach ($game->players as $player) {
            if ($player->slug == $name_slug) {
                $ok = false;
            }
        }
        if ($ok == false) {
            return false;
        } else {
            $guid = random_string('alnum', 16);
            $game->players[] = array(
                'name' => $name,
                'slug' => $name_slug,
                'guid' => $guid,
                'role' => ''
            );
            return $guid;
        }
    }
    
    // generate a slug for the game
    static function generate_slug($name) {
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $name);
        return $slug;        
    }

}