<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1>Please join a game</h1>
            
        </div>
        
    </div>

    <div class="row">
    
        <div class="span12 game-cmd">
            <?php foreach($games as $game) { ?>
            <a class="btn btn-primary btn-large" href="/"><?= $game->name ?></a>
            <?php } ?>
        </div>
                
    </div>


</div>

