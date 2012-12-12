<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1>Join game <?= $game->name ?></h1>
        </div>
        
        <div class="span4 offset4">
            <?= validation_errors() ?>
            <?php if ($message != false) { echo '<div class="alert alert-error">'.$message.'</div>'; } ?>
            <?= form_open('games/join') ?>
                <?= form_hidden('postback', '1'); ?>                    
                <fieldset>
                    <label>Player Name</label>
                    <?= form_input('player_name', $player_name); ?>
                    
                    <br />
                    <button type="submit" class="btn btn-primary btn-large">Join</button>
                </fieldset>
            </form>
        </div>
        
    </div>

</div>

