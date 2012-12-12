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