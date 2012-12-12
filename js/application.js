var covertmission = function() {
    
    // get a description of game statis
    function get_state_description(state) {
        if (state == 'joining') {
            return 'Waiting to start';
        } else if (state == 'starting') {
            return 'Starting game';
        } else if (state == 'mission-selection') {
            return 'Waiting for team leader';
        } else {
            return 'Unknown state: '+state;
        }
    }
    
    // do things at startup
    $(document).ready(function() {
        
        // set the background depending on player role        
        if ($('.player').length) {
            if ($('.player').attr('data-role') == "Imperial Officer") {
                $.backstretch("/img/imperial-bg.jpg");
            } else {
                $.backstretch("/img/rebels-bg.jpg");
            }            
        } else {
            $.backstretch("/img/bg.jpg");
        }
        
        // set team - require team leader to set correct number of players
        // and keeps the hidden field updated
        $('form.set-team').submit(function(event) {
            var selected_count = $('button[data-toggle].active').length;
            if (selected_count != $('[data-team-size]').attr('data-team-size')) {
                event.preventDefault();
                $('.validation-message').addClass('alert alert-error').html("Please select the correct number of players for your team");
            }
        });
        
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
                           if (json_data['url'] != '') {
                               if (window.location.pathname != json_data['url']) {
                                   window.location = json_data['url'];
                               }
                           }
                       }
                   }
                });
                
                $('.game_state')
                
            }, 5000);
        }
        
    });


}();