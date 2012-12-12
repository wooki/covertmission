<?php
class Game {
    
    var $slug = '';
    var $name = '';
    var $state = '';
    var $admin_name = '';
    var $players = array();
    var $current_mission = false;
    var $current_team = false;
    
    // work out the correct url for viewing this game
    static function get_url($game) {
        if ($game->state == 'joining') {
            return '/games/lobby/'.$game->slug; 
        } else if ($game->state == 'starting') {
            return '/games/start/'.$game->slug; 
        } else {
            return false;
        }    
    }
    
    // work out how many spies there should be
    static function how_many_spies($game) {
        return round(count($game->players) * 0.33);
    }
    
    // work out how many agents there should be
    static function how_many_agents($game, $mission_index) {
        $agents = array(
            array(2, 2, 2, 3, 3, 3),
            array(3, 3, 3, 4, 4, 4),
            array(2, 4, 3, 4, 4, 4),
            array(3, 3, 4, 5, 5, 5),
            array(3, 4, 4, 5, 5, 5)
        );
        return $agents[$mission_index][count($game->players)];
    }
    
    // work out how many fail cards are required
    static function how_many_fails($game, $mission_index) {
        $agents = array(
            array(1, 1, 1, 1, 1, 1),
            array(1, 1, 1, 1, 1, 1),
            array(1, 1, 1, 1, 1, 1),
            array(1, 1, 2, 2, 2, 2),
            array(1, 1, 1, 1, 1, 1)            
        );
        return $agents[$mission_index][count($game->players)];
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
    
    // check if all players are in the specified state
    static function all_players_state($game, $state) {
        foreach ($game->players as $player) {
            if ($player->state != $state) {
                return false;
            }
        }
        return true;
    }
    
    // get leader
    static function get_leader($game) {
        foreach ($game->players as $player) {
            if ($player->leader == true) {
                return true;
            }
        }
        return false;
    }
    
    // update an existing player with new data
    static function update_player($game, $player) {
        foreach ($game->players as &$p) {
            if ($p->slug == $player->slug) {
                $p->name = $player->name;
                $p->slug = $player->slug;
                $p->guid = $player->guid;
                $p->leader = $player->leader;
                $p->role = $player->role;
                $p->state = $player->state;
            }
        }
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
                'leader' => 'false',
                'role' => '',
                'state' => 'joining'
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