<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Games extends CI_Controller {

    public function index() {

        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();

        $guid = $this->session->userdata('player_id');

        $this->view_data = array(
            'title' => 'Joinable Games - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'games' => $games_list->games,
            'guid' => $guid,
            'game_name' => ''
        );

        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/index', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);

	}

    public function start($slug=false) {

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
        }

        if ($game->state == 'joining') {
            // first player sets up the data

            // default to imperial
            foreach ($game->players as &$player) {
                $player->role = "Imperial Officer";
            }

            // assign spies
            $spy_count = Game::how_many_spies($game);
            $spy_keys = array_rand($game->players, $spy_count);
            foreach ($spy_keys as $spy_key) {
                $game->players[$spy_key]->role = "Rebel Spy";
            }

            // assign a random leader
            $leader_key = array_rand($game->players, 1);
            $game->players[$leader_key]->leader = true;

            // finally update the state, save and display game
            $game->state = 'starting';
            if (Game::save($game, $games_list) == false) {
                $this->session->set_flashdata('error', 'Error saving game');
                redirect('/', 'location');
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

            $player->state = 'starting';
            Game::update_player($game, $player);

            // set view data
            $this->view_data = array(
                'title' => $game->name.' Role Assignment - Covert Mission - Group game with a star wars theme',
                'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
                'game' => $game,
                'player' => $player,
                'spies' => Game::get_spies($game),
                'game_name' => $game->slug
            );

            $this->load->view('shared/_header.php', $this->view_data);
            $this->load->view('games/role_assign', $this->view_data);
            $this->load->view('shared/_footer.php', $this->view_data);

        } else if ($game->state == 'starting') {

            // load current player
            $guid = $this->session->userdata('player_id');
            $player = Game::get_player($game, $guid);

            if ($player == false) {
                $this->session->set_flashdata('error', 'You are not a player in that game');
                redirect('/', 'location');
                return;
            }

            $player->state = 'starting';
            Game::update_player($game, $player);

            // set view data
            $this->view_data = array(
                'title' => $game->name.' Role Assignment - Covert Mission - Group game with a star wars theme',
                'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
                'game' => $game,
                'player' => $player,
                'spies' => Game::get_spies($game),
                'game_name' => $game->slug
            );

            $this->load->view('shared/_header.php', $this->view_data);
            $this->load->view('games/role_assign', $this->view_data);
            $this->load->view('shared/_footer.php', $this->view_data);

        } else {
            $this->session->set_flashdata('error', 'Game has already started, cannot start it');
            redirect('/', 'location');
            return;
        }


    }

    // join game and redirect to lobby
    public function join($slug=false) {

        if ($slug == false) { show_404('page'); }

        $message = false;

        // load the game
        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();
        $game = Game::load($slug, $games_list);

        if ($game == false) {
            $this->session->set_flashdata('error', 'Game does not exist');
            redirect('/', 'location');
            return;
        }

        if ($game->state != 'joining') {
            $this->session->set_flashdata('error', 'Game has already started, cannot join');
            redirect('/', 'location');
            return;
        }

        // get the player
        $guid = $this->session->userdata('player_id');
        $player = Game::get_player($game, $guid);
        if ($player != false) {
            // already in this game - so just go to the correct action
            $this->_redirect_existing_player($game);
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');

        $player_name = '';
        if ($this->input->post('postback') === '1') {

            $player_name = $this->input->post('player_name');
            $this->form_validation->set_rules('player_name', 'Player Name', 'required');
            if ($this->form_validation->run() == true) {

                // add player and jump into game
                $guid = Game::add_player($game, $player_name);

                if ($guid != false) {
                    $this->session->set_userdata('player_id', $guid);
                    if (Game::save($game, $games_list) == true) {
                        $message = "saved";

                        $this->_redirect_existing_player($game);
                        return;
                    } else {
                        $message = "Error saving game";
                    }
                } else {
                    $message = "A player with that name is already playing this game";
                }
            }
        }

        $this->view_data = array(
            'title' => 'Join '.$game->name.' - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game' => $game,
            'player_name' => $player_name,
            'message' => $message,
            'game_name' => $game->slug
        );

        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/join', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);

	}


    public function lobby($slug=false) {

        if ($slug == false) { show_404('page'); }

        // load the game
        $this->load->helper('form');
        $this->load->model(array('game_list', 'game'));
        $games_list = new Game_List();
        $games_list->load();
        $game = Game::load($slug, $games_list);

        if ($game == false) {
            $this->session->set_flashdata('error', 'Lobby does not exist');
            redirect('/', 'location');
            return;
        }

        // get the player
        $guid = $this->session->userdata('player_id');
        $player = Game::get_player($game, $guid);

        if ($player == false) {
            $this->session->set_flashdata('error', 'You are not a player in that game');
            redirect('/', 'location');
            return;
        }

        $this->view_data = array(
            'title' => $game->name.' Lobby - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game' => $game,
            'player' => $player,
            'game_name' => $game->slug
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
        $games_list = new Game_List();
        $games_list->load();

        if ($this->input->post('postback') === false) {
            $game = Game::create('', '');
            $this->_render_form($game);
        } else {
            $game = Game::create($this->input->post('name'), $this->input->post('admin_name'));

            $this->form_validation->set_rules('name', 'Game Name', 'callback_gamename_check');
            $this->form_validation->set_rules('admin_name', 'Player Name', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->_render_form($game);
            } else {

                // add the admins player and save the guid to the players session
                $guid = Game::add_player($game, $game->admin_name);
                $this->session->set_userdata('player_id', $guid);

                // save the game and redirect to the game lobby
                if (Game::save($game, $games_list) == true) {
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
        if ($name == '') {
            $this->form_validation->set_message('gamename_check', '%s is a required field');
            return false;
        }
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

    public function _redirect_existing_player($game) {
        $url = Game::get_url($game);
        if ($url != false) {
            redirect($url, 'location');
        } else {
            $this->session->set_flashdata('error', 'Game is in unkown state: '.$game->state);
            redirect('/', 'location');
        }
    }

    public function _render_form($game, $message=false) {
        $this->view_data = array(
            'message' => $message,
            'game' => $game,
            'title' => 'New Game - Covert Mission - Group game with a star wars theme',
            'description' => 'Covert Mission is a group game with a star wars theme based around player deception and deduction of player motives, in the same genre as werewolf and mafia.',
            'game_name' => $game->slug
        );

        $this->load->view('shared/_header.php', $this->view_data);
        $this->load->view('games/new', $this->view_data);
        $this->load->view('shared/_footer.php', $this->view_data);
    }

}