<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> Team <?= $vote_result ?></h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Mission vote complete</p>
            
            <p>Mission leader is: <?= player_label($leader->name) ?></p>
            
            <p>Team leader has picked:</p>
            <?php foreach ($team as $p) { ?>
                <?= player_label($p->name) ?>
            <?php } ?>
            
            <?= form_open('missions/vote/'.$game->slug, array('class' => 'vote-acknowledge')) ?>
                <?= form_hidden('postback', '1'); ?>
                  
                <p>Acknowledge vote result: <span class="label label-info"><?= $vote_result ?></span></p>
                
                <p class="validation-message">&nbsp;</p>
                
                <button type="submit" class="btn btn-primary btn-large">Acknowledge</button>

            </form>            
            
        </div>
        
    </div>

</div>

