<?php
class Missions extends CI_Controller {

    // handle post from team leader of team
    // move to approve team state and await
    // player votes
    public function approve($slug=false) {
        
        if ($slug == false) { show_404('page'); }
        
        // load the game
        $this->load->helper('form');
        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();
        $game = Game::load($slug, $games_list);
        
        if ($game == false) {
            $this->session->set_flashdata('error', 'Game does not exist');
            redirect('/', 'location'); 
            return;
        } else if ($game->state != 'mission-selection' && $game->state != 'mission-approve') {
            $this->session->set_flashdata('error', 'Game state has moved');
            redirect(Game::get_url($game), 'location'); 
            return;
        }
        
        // load current player
        $guid = $this->session->userdata('player_id');
        $player = Game::get_player($game, $guid);

        if ($player == false) {
            $this->session->set_flashdata('error', 'You are not a player in that game');
            redirect('/', 'location'); 
            return;
        }
    
        // when the first player reaches this point update game status
        // and look for posted player data
        if (Game::all_players_state($game, 'mission-selection') == true) {
            $game->state = 'mission-approve';            
            
            $team_data = 
        }
        
        // update players state to acknowledge they are on this page
        $player->state = 'mission-approve';      
        Game::update_player($game, $player);
    
        Game::save($game, $games_list);
        
        // set view data
        $this->view_data = array(
            'title' => $game->name.' Mission Approve - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game' => $game,
            'player' => $player,
            'leader' => Game::get_leader($game),
            'team' => $team
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('missions/approve', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
    }
    
    
    
    // display, who the leader is - and allow the leader to 
    // select the correct number of players for the mission
    // also display mission number
    public function selection($slug=false) {
        
        if ($slug == false) { show_404('page'); }
        
        // load the game
        $this->load->helper('form');
        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();
        $game = Game::load($slug, $games_list);
        
        if ($game == false) {
            $this->session->set_flashdata('error', 'Game does not exist');
            redirect('/', 'location'); 
            return;
        } else if ($game->state != 'starting' && $game->state != 'mission-selection') {
            $this->session->set_flashdata('error', 'Game state has moved');
            redirect(Game::get_url($game), 'location'); 
            return;
        }
        
        // load current player
        $guid = $this->session->userdata('player_id');
        $player = Game::get_player($game, $guid);

        if ($player == false) {
            $this->session->set_flashdata('error', 'You are not a player in that game');
            redirect('/', 'location'); 
            return;
        } else {
            // update players state to acknowledge they are on this page
            $player->state = 'mission-selection';      
            Game::update_player($game, $player);
        }
        
        // when the first person hits this page set the mission index
        if ($game->current_mission = false) {
            $game->current_mission = 0;                        
            $game->current_team = 0;
        }
        
        // when the last player reaches this point update game status
        if (Game::all_players_state($game, 'mission-selection') == true) {
            $game->state = 'mission-selection';            
        }
        Game::save($game, $games_list);
        
        // set view data
        $this->view_data = array(
            'title' => $game->name.' Mission Selection - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game' => $game,
            'player' => $player,
            'leader' => Game::get_leader($game),
            'team_size' => Game::how_many_agents($game, $game->current_mission)
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('missions/selection', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
    }
    

    

}