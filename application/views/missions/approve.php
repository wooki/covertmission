<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> Team Approve</h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Waiting for player votes</p>
            
            <p>Mission leader <?= $game->current_team+1 ?> is: <?= player_label($leader->name) ?></p>
            
            <p>Team leader has picked:</p>
            <?php foreach ($team as $p) { ?>
                <?= player_label($p->name) ?>
            <?php } ?>
            
            <? if ($player->vote == '') { ?>
            <?= form_open('missions/approve/'.$game->slug, array('class' => 'vote')) ?>
                <?= form_hidden('vote', ''); ?>
                  
                <p>Vote to approve or reject the leaders team</p>
                
                <div class="btn-group" data-toggle="buttons-radio">
                <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button">Approve</button>
                <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button">Reject</button>
                </div>
                
                <p class="validation-message">&nbsp;</p>
                
                <button type="submit" class="btn btn-primary btn-large">Vote</button>

            </form>            
            <? } else { ?>
            <p>Voted: <span class="label label-info"><?= $player->vote ?></span></p>
            <? } ?>
        </div>
        
    </div>

</div>

