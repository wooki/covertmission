<div class="container page lobby">

    <div class="row">

        <div class="span12">
            <h1><?= $game->name ?> Mission <?= display_mission($game->current_mission) ?> <?= $game->last_mission ?></h1>
            <h4 class="player" data-role="<?= $player->role ?>" data-slug="<?= $player->slug ?>" data-admin="<?= $game->admin_name ?>">Playing as <?= $player->name ?></h4>
        </div>

        <div class="span4 offset4">

            <p class="alert alert-info">The Empire Failed To Select a Mission Team</p>

            <h2>Score</h2>
            <p>Empire: <span class="label label-inverse"><?= $game->success ?></span></p>
            <p>Rebels: <span class="label label-important"><?= $game->fail ?></span></p>

            <h2><span class="label label-important">The Rebels</span> have won the game!</h2>
            Well done:<div class="winners">
            <?php foreach ($game->players as $p) { ?>
            <?php if ($p->role == "Rebel Spy") { ?>
            <?= player_label($p->name) ?>
            <?php } ?>
            <?php } ?>
            </div>

            <?php if ($player->state == 'mission-result') {  ?>
            <?= form_open('missions/result/'.$game->slug) ?>
                <?= form_hidden('postback', 'acknowledge'); ?>
                <button type="submit" class="btn btn-primary btn-large">Continue</button>
            </form>
            <?php } else {  ?>
            <p>Please wait for all players</p>
            <?php } ?>
        </div>

    </div>

</div>

