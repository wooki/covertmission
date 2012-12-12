var covertmission = function() {
    
    // get a description of game statis
    function get_state_description(state) {
        if (state == 'joining') {
            return 'Waiting to start';
        } else if (state == 'starting') {
            return 'Starting game';
        } else {
            return 'Unknown state: '+state;
        }
    }
    
    // do things at startup
    $(document).ready(function() {
        
        // set the background depending on player role        
        $.backstretch("/img/rebels-bg.jpg");
        
        // lobby - refresh status every n seconds and update players
        if ($('.game_state').length) {
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
                       
                       // if we are admin and have enough players refresh lobby page - to reveal the start button
                       if ($('.admin_controls_waiting').length && $(json_data['players']).length >= 5) {
                           window.location.reload();
                       } else {
                           // if the url is different from current then redirect
                           $('.game_state').html(window.location.pathname+' or '+json_data['url']);
                       }
                   }
                });
                
                $('.game_state')
                
            }, 5000);
        }
        
    });


}();