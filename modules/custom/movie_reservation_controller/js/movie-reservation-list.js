jQuery( "#submit" ).click(function(e) {

    jQuery("#movie_reservations").text('');

    jQuery.ajax({
        method:'GET',
        url:'/get-reservations-data/'+jQuery("#orders").val().split("|")[0]+'/'+jQuery("#orders").val().split("|")[1],
        success:function(response)
            {

            response["data"].forEach(response => {


                jQuery("#movie_reservations")
                .append(
                    jQuery(document.createElement('h1'))
                    .prop({id: 'reservation_title'})
                    .html(response.reserved_movie_name + ' reservation')
                )
                .append(
                    jQuery(document.createElement('h4'))
                    .prop({id: 'reservation_customer_name'})
                    .html('Reserved by: ' + response.customer_name)
                )
                .append(
                    jQuery(document.createElement('p'))
                    .prop({id: 'reservation_day_and_time'})
                    .html('Reserfed for: ' + response.day_of_reservation + ', and reservation was made on: ' + response.time_of_reservation)
                )

            });

        }

    }); 

});