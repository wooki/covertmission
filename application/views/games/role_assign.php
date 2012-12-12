<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Role Assignment</h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p data-game-state="joining" class="alert alert-info game_state">Starting game</p>
            
            <p>You are playing as a<?php if ($player->role == "Imperial Officer") { echo 'n';  } ?> <strong><?= $player->role ?></strong>. You
            must complete 3 our of the 5 missions that you will be set to win the game.</p>
            
            <p>A team leader will be assigned for each mission and they then select the operatives to take part in the mission. Not every player
            will be sent on each mission. Once the mission team has been assembled all players vote to accept or reject the team. If the team
            is rejected a new team leader will be selected and a new team assembled. If the mission is accepted then the operatives will attempt
            to complete the mission. Spies will try to saboutage the mission.</p>
            
            <p>Note that five rejected teams in a row will automatically lead to a win to the Rebel Spies.</p>
            
            <?= form_open('mission/selection/'.$game->slug) ?>
                <fieldset>
                    <button type="submit" class="btn btn-primary btn-large">Start Mission Selection</button>
                </fieldset>
            </form>            
            
        </div>
        
    </div>

</div>

