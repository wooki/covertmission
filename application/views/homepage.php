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

            <p style="text-align: center;">You are Imperial or Rebel</p>
            <p style="text-align: center;">Team leader picks a team</p>
            <p style="text-align: center;">Accept/Reject the team</p>
            <p style="text-align: center;">Team vote on success of mission</p>
            <p style="text-align: center;">First side to get 3 wins</p>

            <p style="text-align: center;"><a class="btn" href="/about">More Info</a></p>

        </div>
    </div>

</div>

