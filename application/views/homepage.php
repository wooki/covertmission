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

            <ul>
            <li>You are Imperial or Rebel</li>
            <li>Team leader picks a team</li>
            <li>Accept/Reject the team</li>
            <li>Team vote on success of mission</li>
            <li>First side to get 3 wins</li>
            </ul>

            <a class="btn btn-primary btn-large" href="/about">More Info</a>


        </div>
    </div>

</div>

