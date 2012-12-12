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
                    <div class="btn-group" data-toggle="buttons-checkbox">
                        <?php foreach ($game->players as $p) { ?>
                        <button type="button" class="btn  btn-large"><i class="icon-user"></i><?= $p->name ?></button>
                        <?php } ?>
                    </div>
                
                    <button type="submit" class="btn btn-primary btn-large">Set Team</button>
                </fieldset>
            </form>            
            <?php }  ?>
        </div>
        
    </div>

</div>

