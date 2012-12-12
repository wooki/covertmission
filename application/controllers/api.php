<?php
class Api extends CI_Controller {

    // returns the state of the specified game
    public function state($slug=false) {
        
        if ($slug == false) { show_404('page'); }
        
        $game = $this->_load_game($slug);
        if ($game != false) {
            
            $game_url = Game::get_url($game);
            if ($game_url == false) {
                $game_url = 'jquery ';
            }
            echo json_encode(array(
                                'return' => $game->state,
                                'url' => $game_url
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