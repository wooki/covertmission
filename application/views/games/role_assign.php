<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Role Assignment</h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p data-game-state="starting" class="alert alert-info game_state">Starting game</p>
            
            <p>You are playing as a<?php if ($player->role == "Imperial Officer") { echo 'n';  } ?>
            <strong><?= player_role($player->role) ?></strong></p>
            
            <?php if ($player->role == "Rebel Spy") { ?>
                <p>Other Rebel Spies: <?php foreach ($spies as $spy) { ?>
                <?php if ($spy->slug != $player->slug) { echo player_label($spy->name); } ?>
                <?php } ?></p>                
            <?php } else { ?>
                <p>Note: Rebel Spies will know who each other are!</p>
            <?php } ?>
            
            <p>Each mission will have a team leader that selects the team - you may approve or
            reject this team.</p>
            
            <p>Remember, you must complete 3 our of the 5 missions to win the game.</p>
            
            <?= form_open('missions/selection/'.$game->slug) ?>
                <fieldset>
                    <button type="submit" class="btn btn-primary btn-large">Start Mission Selection</button>
                </fieldset>
            </form>            
            
        </div>
        
    </div>

</div>

