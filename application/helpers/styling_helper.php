<?php

// display the players role in a twitter bootstrap label
if ( ! function_exists('player_role')) {
    function player_role($role) {
    	if ($role == "Imperial Officer") {
            return '<strong class="label label-inverse">'.$role.'</strong>';
		} else if ($role == "Rebel Spy") {
            return '<strong class="label label-important">'.$role.'</strong>';
		} else {
    	    return '<strong class="label label-success">'.$role.'</strong>';
    	}
	}
}

// display the mission number (one higher to account for zero based array)
if ( ! function_exists('display_mission')) {
    function display_mission($index) {
    	return $index+1;
	}
}

// display a player label
if ( ! function_exists('player_label')) {
    function player_label($name) {
        return '<span class="label label-info"><i class="icon-user icon-white"></i>'.$name.'</span>';
	}
}