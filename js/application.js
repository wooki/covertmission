$(document).ready(function() {
    
    // set the background depending on player role
    
    $.backstretch("/img/rebels-bg.jpg");
    
    // lobby - refresh status every n seconds and update players
    if ($('.lobby').length) {
        // <p data-game-state="joining" class="alert alert-info game_state">Waiting to start</p>
        setInterval(function() {
            
            $.ajax({
               url: '/api/state/test',
               error: function(xhr, textStatus, error) {
                   alert('Error: '+textStatus);
               },
               success: function(data, textStatus, xhr) {
                   alert('Success: '+textStatus+' : '+data);
               }
            });
            
            $('.game_state')
            
        }, 3000);
    }
    
});