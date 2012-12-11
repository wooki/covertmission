<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Lobby</h1>
            <h4>Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Waiting to start</p>
            
            <p>The admin can start the time as soon as there are five or more 
            players.  Each player will then be given a secret role - either
            an Imperial Officer or as a Rebel Alliance Spy.</p>
            
            <p><strong>Keep your role secret!</strong></p>
            
            <?php if ($game->admin_name == $player->name) { ?>
                <p>ADMIN START GAME CONTROLS GO HERE</p>
            <?php } ?>
        </div>
        
    </div>

</div>

