<div class="container page">

    <div class="row">

        <div class="span12">
            <h1>Welcome to Covert Mission</h1>
        </div>

    </div>

    <?php if ($error != false) { echo '<div class="row"><div class="span4 offset4 game-cmd"><div class="alert alert-error">'.$error.'</div></div></div>'; } ?>

    <div class="row">

        <div class="span2 offset4 game-cmd">
            <a class="btn btn-primary btn-large" href="/games/create">New Game</a>
        </div>
        <div class="span2 game-cmd">
            <a class="btn btn-primary btn-large" href="/games">Join</a>
        </div>

    </div>

    <div class="row">

      <div class="span4 offset4">

            <h2>Gameplay</h2>
            <h3><i class="icon-ok"></i>You are Imperial or Rebel</h3>
            <h3><i class="icon-ok"></i>Team leader picks a team</h3>
            <h3><i class="icon-ok"></i>Accept/Reject the team</h3>
            <h3><i class="icon-ok"></i>Team vote on success of mission</h3>
            <h3><i class="icon-ok"></i>First side to get 3 wins</h3>

            <p><a class="btn" href="/about">More Info</a></p>


        </div>
    </div>

</div>

