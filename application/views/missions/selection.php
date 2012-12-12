<div class="container page lobby">

    <div class="row">
    
        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> Team Selection</h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>            
        </div>
        
        <div class="span4 offset4">
            
            <p class="alert alert-info game_state">Waiting for team leader</p>
            
            <p>Mission leader is: <?= player_label($leader->name) ?></p>
            
            <?php if ($player->leader === true) { ?>            
            <?= form_open('missions/selection/'.$game->slug) ?>
                <fieldset>
                    
                    <p>Discuss and select your team:</p>
                    
                    <div class="padding">
                    
                        <?php foreach ($game->players as $p) { ?>
                        <button style="margin-bottom: 0.5em;" type="button" class="btn btn-large" data-toggle="button"><i class="icon-user"></i> <?= $p->name ?></button>
                        <?php } ?>
                        
                        <p>&nbsp;</p>
                        
                        <button type="submit" class="btn btn-primary btn-large">Set Team</button>
                    </div>
                </fieldset>
            </form>            
            <?php }  ?>
        </div>
        
    </div>

</div>

