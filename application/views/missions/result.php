<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> <?= $game->last_mission ?></h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Waiting for all players</p>
            
            <h2>Score</h2>
            <p>Empire: <?= $game->success ?></p>
            <p>Rebels: <?= $game->fail ?></p>
            
            <?php if ($game->success > 2) { ?>
                <h2>The Empire have won the game!</h2>
                <div class="players">Well done:
                <?php foreach ($game->players as $p) { ?>
                <?php if ($p->role == "Imperial Officer") { ?>
                <?= player_label($p->name) ?>
                <?php } ?>
                <?php } ?>
                </div>                
            <?php } else if ($game->fail > 2) { ?>
                <h2>The Rebels have won the game!</h2>
                <div class="players">Well done:
                <?php foreach ($game->players as $p) { ?>
                <?php if ($p->role == "Rebel Spy") { ?>
                <?= player_label($p->name) ?>
                <?php } ?>
                <?php } ?>
                </div>
            <?php } ?>
            
            <?= form_open('missions/result/'.$game->slug) ?>
                <?= form_hidden('postback', 'acknowledge'); ?>
                <button type="submit" class="btn btn-primary btn-large">Continue</button>
            </form>   
        </div>
        
    </div>

</div>

