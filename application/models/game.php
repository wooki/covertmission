<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Game {

    var $slug = '';
    var $name = '';
    var $state = '';
    var $admin_name = '';
    var $players = array();
    var $current_mission = false;
    var $current_team = false;
    var $success = 0;
    var $fail = 0;
    var $last_mission = false;
    var $updated = '';

    // work out the correct url for viewing this game
    static function get_url_for_state($state, $slug) {
        if ($state == 'joining') {
            return '/games/lobby/'.$slug;
        } else if ($state == 'starting') {
            return '/games/start/'.$slug;
        } else if ($state == 'mission-selection') {
            return '/missions/selection/'.$slug;
        } else if ($state == 'mission-approve') {
            return '/missions/approve/'.$slug;
        } else if ($state == 'mission-vote') {
            return '/missions/vote/'.$slug;
        } else if ($state == 'mission-vote-acknowledge') {
            return '/missions/vote/'.$slug;
        } else if ($state == 'mission-execute') {
            return '/missions/execute/'.$slug;
        } else if ($state == 'mission-watch') {
            return '/missions/execute/'.$slug;
        } else if ($state == 'mission-result') {
            return '/missions/result/'.$slug;
        } else if ($state == 'mission-failed') {
            return '/games/failed/'.$slug;
        } else if ($state == 'game-over') {
            return '/';
        } else {
            return false;
        }
    }

    // util for calling the above function
    static function get_url($game) {
        return Game::get_url_for_state($game->state, $game->slug);
    }

    // work out how many spies there should be
    static function how_many_spies($game) {
        $spies = array(2, 2, 3, 3, 3, 4);
        return $spies[count($game->players)-5];
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
        return $agents[$mission_index][count($game->players)-5];
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
        return $agents[$mission_index][count($game->players)-5];
    }

    // load from disc, from json file
    static function load($slug, $game_list) {
        return $game_list->get_game($slug);
    }

    // save to disc, as json
    static function save($game, $game_list) {
        $game->updated = date(DateTime::ATOM);
        return $game_list->update_game($game);
    }

    // create a new game
    static function create($name, $admin_name) {
        $g = new Game();
        $g->name = $name;
        $g->admin_name = $admin_name;
        $g->slug = Game::generate_slug($g->name);
        $g->state = "joining";
        $g->success = 0;
        $g->fail = 0;
        $g->last_mission = false;
        $g->updated = date(DateTime::ATOM);
        return $g;
    }

    // record mission success and move state, plus ready
    // for next mission
    static function mission_success($game) {
        $game->state = "mission-result";
        $game->success++;
        $game->last_mission = "Success";
        Game::next_team($game); // reset team and next leader
        $game->current_team = 0;
        $game->current_mission++;
    }

    // record mission fail and move state, plus ready
    // for next mission
    static function mission_fail($game) {
        $game->state = "mission-result";
        $game->fail++;
        $game->last_mission = "Fail";
        Game::next_team($game); // reset team and next leader
        $game->current_team = 0;
        $game->current_mission++;
    }

    // start mission, resets votes but thats it
    static function start_mission($game) {
        foreach ($game->players as &$player) {
            $player->vote = false;
        }
    }

    // next team number, reset votes and new leader
    static function next_team($game) {
        $game->current_team++;
        $current_leader = false;
        foreach ($game->players as &$player) {
            if ($player->leader == true) {
                $current_leader = true;
                $player->leader = false;
            } else if ($current_leader == true) {
                $player->leader = true;
                $current_leader = false;
                $game->leader_name = $player->name;
            } else {
                $player->leader = false;
            }
            $player->team = false;
            $player->vote = false;
            $player->state = $game->state;
        }

        // if we have still got the current leader then set to 1st player
        if ($current_leader == true) {
            $game->players[0]->leader = true;
        }

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

    // check if all players have voted and return vote result
    static function check_mission_vote($game) {
        $not_voted = 0;
        $voted_yes = 0;
        $voted_no = 0;
        $required_fails = Game::how_many_fails($game, $game->current_mission);
        foreach ($game->players as $p1) {
            if ($p1->state == "mission-execute") {
                if ($p1->vote == "Succeed") {
                    $voted_yes++;
                } else if ($p1->vote == "Fail") {
                    $voted_no++;
                } else {
                    $not_voted++;
                }
            } else if ($p1->state == "mission-vote-acknowledge") {
                $not_voted++;
            }
        }
        if ($not_voted > 0) {
            return "Incomplete";
        } else if ($voted_no >= $required_fails) {
            return "Fail";
        } else {
            return "Success";
        }
    }

    // check if all players have voted and return vote result
    static function check_team_vote($game) {
        $not_voted = 0;
        $voted_yes = 0;
        $voted_no = 0;
        foreach ($game->players as $player) {
            if ($player->vote == "Approve") {
                $voted_yes++;
            } else if ($player->vote == "Reject") {
                $voted_no++;
            } else {
                $not_voted++;
            }
        }
        if ($not_voted > 0) {
            return "Incomplete";
        } else if ($voted_yes > $voted_no) {
            return "Approved";
        } else {
            return "Rejected";
        }
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

    // set current team
    static function set_team($game, $team) {
        foreach ($game->players as &$player) {
            $player->team = false;
        }
        foreach ($team as $p) {
            foreach ($game->players as &$player) {
                if ($player->slug === $p->slug) {
                    $player->team = true;
                }
            }
        }
        return false;
    }

    // get team
    static function get_team($game) {
        $team = array();
        foreach ($game->players as $player) {
            if ($player->team === true) {
                $team[] = $player;
            }
        }
        return $team;
    }

    // get spies
    static function get_spies($game) {
        $spies = array();
        foreach ($game->players as $player) {
            if ($player->role === "Rebel Spy") {
                $spies[] = $player;
            }
        }
        return $spies;
    }

    // get leader
    static function get_leader($game) {
        foreach ($game->players as $player) {
            if ($player->leader === true) {
                return $player;
            }
        }
        return false;
    }

    // update an existing player with new data
    static function update_player($game, $player) {
        foreach ($game->players as $key => $p) {
            if ($game->players[$key]->slug == $player->slug) {
                $game->players[$key]->name = $player->name;
                $game->players[$key]->slug = $player->slug;
                $game->players[$key]->guid = $player->guid;
                $game->players[$key]->leader = $player->leader;
                $game->players[$key]->role = $player->role;
                $game->players[$key]->state = $player->state;
                $game->players[$key]->team = $player->team;
                $game->players[$key]->vote = $player->vote;
                $game->players[$key]->image = $player->image;
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
                'leader' => false,
                'role' => '',
                'state' => 'joining',
                'team' => false,
                'vote' => '',
                'image' => 'empire_01.png'
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