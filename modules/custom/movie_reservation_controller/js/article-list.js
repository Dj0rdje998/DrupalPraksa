  const movie_form = jQuery("#movie_form");
  
  const reserve = jQuery("#reserve").click(openPopup).hide();

  const popup = jQuery("#popup").hide();

  jQuery( ".movies_for_type" ).hide();
  

  jQuery( "#cancel" ).click(cancelPopup);

  jQuery("#submit_popup").click(submitReservation);

  jQuery( "#submit" ).click(function(e) {
    e.preventDefault();

    jQuery.ajax({
      method:'GET',
      url:'http://drupal.praksa/movie/type/'+jQuery("#movie_type").val(),
      success:function(response)
      {

       movie_form.empty();
       reserve.hide();


       response["data"].forEach(response => {

          jQuery('<input />', { type: 'checkbox', id: 'cb' + response.id,  name:"myCheck"}).val( response.id + "|" + response.title + "|"+ response.genre + "|"+ response.reservation_period)
          .on("click",reserveButtonShow).appendTo(movie_form);
          
          jQuery('<label />', { 'for': 'cb' + response.id, text: response.title }).appendTo(movie_form);

          jQuery('<br/>').appendTo(movie_form);
        });

      }
      
    });

   jQuery( ".movies_for_type" ).show();

   

  });

  function reserveButtonShow(){

    if(jQuery("input[type='checkbox']").is(":checked")){
      reserve.show();
    }
    else{
      reserve.hide();
    }

  }

  function openPopup(){

    popup.show();
    

    var array = jQuery.map(jQuery('input[name="myCheck"]:checked'), function(c){
      
      var res = c.value.split("|");

      return {
        id : res[0],
        title : res[1],
        genre : res[2],
        reservation_period : res[3],
      };

      });

      
      popup
        .append(
          jQuery(document.createElement('label')).prop({
            for: 'movies'
          }).html('Choose your reservation date: ')
        )
        .append(
          jQuery(document.createElement('select')).prop({
            id: 'movies',
          })
        )
 
    array.forEach(function (arrayItem) {

       arrayItem.reservation_period.split(",").forEach(element => {
        jQuery("#movies").append(jQuery(document.createElement('option')).prop({
          value:'{"id":'+arrayItem.id+', "title":'+'"'+arrayItem.title +'"'+', "genre":'+ '"' + arrayItem.genre+ '"' +', "reservation_period":'+ '"'+ element + '"' +'}',
          text: arrayItem.title + " " + element
        }))
      })  

    });

  }

  function cancelPopup(){
    popup.hide();
  }

  function submitReservation(){

    jQuery.ajax({
      method:'GET',
      url:'/reservation-upload/'+jQuery("#customer_name").val()+'/'+jQuery("#movies").val(),
      success:function(response)
      {
        jQuery("#popup_response_message").html(response['data']);
      }
      
    });

  }