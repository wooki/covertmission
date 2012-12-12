var covertmission = function() {
    
    // get a description of game statis
    function get_state_description(state) {
        if (state == 'joining') {
            return 'Waiting to start';
        } else {
            return 'Unknown state: '+state;
        }
    }
    
    // do things at startup
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
                       $('.game_state').html('Error: '+textStatus);
                   },
                   success: function(data, textStatus, xhr) {
                       var json_data = JSON.parse(data);
                       $('.game_state').html(get_state_description(json_data['return']));                   
                       var player_html = '';
                       $(json_data['players']).each(function(key, item) {
                           player_html += '<span class="label label-info"><i class="icon-user icon-white"></i>'+item['name']+'</span> ';
                       });
                       $('.players').html(player_html);
                       
                       // if we are admin and have enough players refresh lobby page
                       if ($('.admin_controls').length && $(json_data['players']).length >= 5) {
                           window.location.reload();
                       }
                   }
                });
                
                $('.game_state')
                
            }, 3000);
        }
        
    });


}();