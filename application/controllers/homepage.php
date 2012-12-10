<?php
class Homepage extends CI_Controller {

    public function index() {
		
        $this->view_data = array(
            'title' => 'Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.'
        );
        
        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('homepage', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
        
	}
}