<div class="container page">

    <div class="row">
    
        <div class="span12">
            <h1>Welcome to Covert Mission</h1>
        </div>
        
    </div>
    
    <?php if ($error != false) { echo '<div class="row"><div class="span4 offset4 game-cmd"><div class="alert alert-error fade in">'.$error.'</div></div></div>'; } ?>
    
    <div class="row">
    
        <div class="span3 offset2 game-cmd">
            <a class="btn btn-primary btn-large" href="/games/create">New Game</a>
        </div>
        <div class="span3 offset2 game-cmd">
            <a class="btn btn-primary btn-large" href="/games">Join</a>
        </div>
        
    </div>


</div>

