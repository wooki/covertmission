<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> Team Approve</h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Waiting for player votes</p>
            
            <p>Mission leader is: <?= player_label($leader->name) ?></p>
            
            <p>Team leader has picked:</p>
            <?php foreach ($team as $p) { ?>
                <?= player_label($p->name) ?>
            <?php } ?>
            
            <?= form_open('missions/vote/'.$game->slug, array('class' => 'vote')) ?>
                    
                <p>Vote to approve or reject the leaders team</p>
                
                <div class="btn-group" data-toggle="buttons-radio">
                <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button">Approve</button>
                <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button">Reject</button>
                </div>
                
                <p class="validation-message">&nbsp;</p>
                
                <button type="submit" class="btn btn-primary btn-large">Vote</button>

            </form>            
            <?php } ?>
        </div>
        
    </div>

</div>

