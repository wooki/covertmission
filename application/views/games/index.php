<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1>Please select a game</h1>            
        </div>
        
    </div>

    <div class="row">
    
        <div class="span12 game-cmd">
            <?php foreach($games as $game) { 
                $p = Game::get_player($game, $guid);
                if ($p != false) { ?>
            <a class="btn btn-success btn-large" href="/games/join/<?= $game->slug ?>"><?= $game->name ?></a>
                <?php } else if ($game->state == 'joining') { ?>
            <a class="btn btn-primary btn-large" href="/games/join/<?= $game->slug ?>"><?= $game->name ?></a>                
            <?php }
            } ?>
        </div>
                
    </div>


</div>

