jQuery(document).ready(function($) {

    $('.em-booking-submit').click( function(e) {
        e.preventDefault();

        var data = {
            'action': 'booking_add',
            'whatever': ajax_object.we_value
        };

        var form = $(this).parents('form');
        var fields = form.serializeArray();

        jQuery.each( fields, function( i, field ) {
            data[field.name] = field.value;
        });



        var ticketId = $(this).attr('data-ticket-id');
        data['em_tickets['+ticketId+'][spaces]'] = 1;

        var parent = $(this).parent();
        $(this).hide();


        jQuery.post(ajax_object.ajax_url, data, function(response) {
            alert('Got this from the server: ' + response);
            parent.append(response);
            parent.append($('#add-to-calendar').show());
        });




    });


    // // We can also pass the url value separately from ajaxurl for front end AJAX implementations

});