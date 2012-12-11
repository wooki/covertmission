<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1>Create game</h1>
            <?= form_open('games/create') ?>
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

