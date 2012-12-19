<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// command line scripts for acting on the games file (eg. remove old games)
class Scripts extends CI_Controller {

    // returns the state of the specified game
    public function remove_old_games() {

        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();

        foreach ($games_list->games as $game) {
            echo $game->state."\n";
        }

    }


}