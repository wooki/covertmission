<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1>Create game</h1>
        </div>
        
        <div class="span4 offset4">
            <?= validation_errors() ?>
            <?= form_open('games/create') ?>
                <?= form_hidden('postback', '1'); ?>                    
                <fieldset>
                    <label>Game Name</label>
                    <?= form_input('name', $game->name); ?>
                    
                    <label>Player Name</label>
                    <?= form_input('admin_name', $game->admin_name); ?>
                    
                    <button type="submit" class="btn btn-primary btn-large">Create</button>
                </fieldset>
            </form>
        </div>
        
    </div>

</div>

