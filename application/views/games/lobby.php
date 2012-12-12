<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Lobby</h1>
            <h4 class="player" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p data-game-state="joining" class="alert alert-info game_state">Waiting to start</p>
            
            <p>The admin can start the game as soon as there are five or more 
            players.  Each player will then be given a secret role - either
            an <strong>Imperial Officer</strong> or as a <strong>Rebel Alliance 
            Spy</strong>. Keep your role secret!</p>
            
            <?php 
            if ($game->admin_name == $player->name) { ?>
            <div class="admin_controls">
            <?php if (count($game->players) >= 5 ) {  ?>
            
            <?= form_open('games/start') ?>
                <fieldset>
                    <button type="submit" class="btn btn-primary btn-large">Start</button>
                </fieldset>
            </form>
            
            <?php } else { ?>
            <p>Waiting for five or more players.</p>
            </div>
            <?php } ?>
            <?php } ?>
            </div>
            
            <h4>Players in game</h4>
            
            <div class="players">
            <?php foreach ($game->players as $p) { ?>
            <span class="label label-info"><i class="icon-user icon-white"></i><?= $p->name ?></span>
            <?php } ?>
            </div>
            
        </div>
        
    </div>

</div>

