<?php
class Games extends CI_Controller {

    public function index() {
    	
        $this->view_data = array(
            'title' => 'Joinable Games - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.'
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/index', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
        
	}
    
    public function lobby($slug) {
    	
        // load the game
        $this->load->model('game');
        $game = Game::load($slug);
        if ($game == false) {
            $this->session->set_flashdata('error', 'Lobby does not exist');
            echo "Game not found: ".$slug;
            //redirect('/', 'location'); 
            return;
        }
        
        // get the player
        $guid = $this->session->userdata('player_id');
        $player = Game::get_player($game, $guid);
        if ($player == false) {
            $this->session->set_flashdata('error', 'You are not a player in that game');
            echo "No player: ".$guid;
            //redirect('/', 'location'); 
            return;
        }
        
        $this->view_data = array(
            'title' => $game->name.' Lobby - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game' => $game,
            'player' => $player
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/lobby', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
        
	}
    
    public function create() {
        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
        $this->load->model(array('game_list', 'game'));
        
        if ($this->input->post('postback') === false) {
            $game = Game::create('', '');
            $this->_render_form($game);
        } else {
            $game = Game::create($this->input->post('name'), $this->input->post('admin_name'));
                
            $this->form_validation->set_rules('name', 'Game Name', 'required');
            $this->form_validation->set_rules('gamename_check', 'Game Name', 'callback_gamename_check');
            $this->form_validation->set_rules('admin_name', 'Player Name', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->_render_form($game);    
            } else {
                
                // add the admins player and save the guid to the players session
                $guid = Game::add_player($game, $game->admin_name);
                $this->session->set_userdata('player_id', $guid);
                
                // save the game and redirect to the game lobby
                if (Game::save($game) == true) {
                    
                    // update game list to prevent default game names
                    $games_list = new Game_List();
                    $games_list->load();
                    $games_list->update_game($game->name, $game->slug, $game->state);
                    $games_list->save();
                    
                    redirect('/games/lobby/'.$game->slug, 'location');                    
                } else {
                    $this->_render_form($game, 'Error Saving Game');  
                }                
                
            }
        }
        
        
    }
    
    public function gamename_check($name) {
        $this->load->model(array('game_list', 'game'));
        $slug = Game::generate_slug($name);
        if ($name != '' && $slug == '') {
            $this->form_validation->set_message('gamename_check', '%s is invalid');            
            return false;
        }
        $games_list = new Game_List();
        $games_list->load();
        $game_exists = $games_list->game_exists($name, $slug);
        if ($game_exists) {
            $this->form_validation->set_message('gamename_check', '%s is already in use');    		
        }
        return !$game_exists;
    }
    
    
    public function _render_form($game, $message=false) {
        $this->view_data = array(
            'message' => $message,
            'game' => $game,
            'title' => 'New Game - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.'
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/new', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);    
    }   
    
}