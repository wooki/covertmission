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
    
    public function create() {
        
        $this->load->helper('form');
        $this->load->model('game_list', 'game');
        
        if ($this->input->post('postback') === false) {
            $game = Game::
        } else {
            
        }
        
        $this->view_data = array(
            'game' => $game,
            'title' => 'New Game - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.'
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/new', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
    }
    
    
}