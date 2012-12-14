<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> Execute</h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Waiting for player votes</p>
            
            <p>Mission leader is: <?= player_label($leader->name) ?></p>
            
            <p>Team leader has picked:</p>
            <?php foreach ($team as $p) { ?>
                <?= player_label($p->name) ?>
            <?php } ?>
            
            <? if ($player->state == 'mission-execute') { ?>
            <? if ($player->vote == false) { ?>
            <?= form_open('missions/execute/'.$game->slug, array('class' => 'execute')) ?>
                <?= form_hidden('execute', ''); ?>
                  
                <p>Vote for the mission to succeed:</p>
                
                <div class="btn-group" data-toggle="buttons-radio">
                <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button">Succeed</button>
                <?php if ($player->role == "Rebel Spy") { ?>
                <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button">Fail</button>
                <?php } else { ?>
                (only Rebels can fail a mission)
                <?php } ?>
                </div>
                
                <p class="validation-message">&nbsp;</p>
                
                <button type="submit" class="btn btn-primary btn-large">Vote</button>

            </form>            
            <? } else { ?>
            <p>Please wait while the rest of the team vote.</p>
            <? }
            } else { ?>
            <p>Please wait while the team conduct the mission.</p>
            <? } ?>
        </div>
        
    </div>

</div>

