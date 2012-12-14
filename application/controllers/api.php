<?php
class Api extends CI_Controller {

    // returns the state of the specified game
    public function state($slug=false) {
        
        if ($slug == false) { show_404('page'); }
        
        $game = $this->_load_game($slug);
        if ($game != false) {
            
            $players = array();
            foreach ($game->players as $player) {
                $players[] = array('name' => $player->name,
                                   'slug' => $player->slug
                                   );
            }
            
            $game_url = Game::get_url($game);
            if ($game_url == false) {
                $game_url = '';
            }
            
            // load current player
            $guid = $this->session->userdata('player_id');
            $player = Game::get_player($game, $guid);
    
            if ($player != false) {
            }
            
            // special case to ensure players have to manually move past
            // the role assignment page
            if ($game->state == "starting" &&
                Game::all_players_state($game, 'joining') == false) {
                $game_url = '';
            } else if ($game->state == "mission-selection" && 
                       $player->state == "mission-approve") {
                $game_url = Game::get_url_for_state("mission-approve", $game->slug);
            }
            
            echo json_encode(array(
                                'return' => $game->state,
                                'url' => $game_url,
                                'players' => $players
                            ));
        }
    }
    
    private function _load_game($slug) {
        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();
        $game = Game::load($slug, $games_list);
        
        if ($game == false) {
            echo json_encode(array('return' => 'Game does not exist'));
        }
        return $game;
    }
    

}