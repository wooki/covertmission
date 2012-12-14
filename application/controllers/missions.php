<?php
class Missions extends CI_Controller {

    // display mission vote result and wait for final Continue click from players
    // where we either revert to new leader and vote or go to the mission.
    public function vote($slug=false) {
        
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
        } else if ($game->state != 'mission-vote') {
            $this->session->set_flashdata('error', 'Game state has moved');
            //redirect(Game::get_url($game), 'location'); 
            //return;
echo "<p>".$game->state."</p>";        
        }
        
        // load current player
        $guid = $this->session->userdata('player_id');
        $player = Game::get_player($game, $guid);

        if ($player == false) {
            $this->session->set_flashdata('error', 'You are not a player in that game');
            redirect('/', 'location'); 
            return;
        }
    
        // first time player hits page set state, if they are posting back then update
        $postback = $this->input->post('postback');
echo "<p>".$postback."</p>";        
        if ($postback == '1') {            
            $player->state = 'mission-vote-acknowledge';  
        } else {
            $player->state = 'mission-vote';  
        }        
        Game::update_player($game, $player);    
        
        // check success/fail
        $vote_result = Game::check_vote($game);
echo "<p>".print_r($vote_result, true)."</p>";        
        // when all players have acknowledged we redirect top relevant step
echo "<p>".print_r($game->players, true)."</p>";        
        if (Game::all_players_state($game, 'mission-vote-acknowledge') == true) {
           
           // fail 5th - Game Over
           if ($vote_result == "Rejected" && $game->current_team >= 5) {
                $this->session->set_flashdata('error', 'TODO: Redirect to Game Over!'); 
                redirect('/');
               
           } else if ($vote_result == "Rejected") {
            // fail - next team leader
            $game->state = "mission-selection";
            Game::next_team($game);
echo "REDIRECT!"            
            redirect(Game::get_url($game));
            
           } else if ($vote_result == "Approved") {
            // success - go to mission
            
           } else {
               $this->session->set_flashdata('error', 'Mission Vote Error: '.$vote_result);            
               redirect('/');
           }
        }
        
        // finally save the game
        Game::save($game, $games_list);
        
        // set view data
        $this->view_data = array(
            'title' => $game->name.' Mission Vote - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game' => $game,
            'player' => $player,
            'leader' => Game::get_leader($game),
            'team' => Game::get_team($game),
            'vote_result' => $vote_result
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('missions/vote', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
    }
    
    
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
            $team_data = explode("|", $this->input->post('team'));
        
            // assemble the team
            $team = array();
            foreach($game->players as $p) {
                if (in_array($p->slug, $team_data)) {
                    $team[] = $p;
                }
            }
            
            // save to game
            Game::set_team($game, $team);
            
        } else {
            // get the team from the game
            $team = Game::get_team($game);
        }
        
        // update players state to acknowledge they are on this page
        $player->state = 'mission-approve';  
        
        // set the players vote IF there is one
        $vote = $this->input->post('vote');
        if ($vote != false) {
            $player->vote = $vote;
        }
        Game::update_player($game, $player);    
        
        
        // check if we have all votes
        if (Game::check_vote($game) != "Incomplete") {
            // update state to mission-vote to redirect to show result page
            $game->state = "mission-vote";
        }
        
        // finally save the game
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