  const movie_form = jQuery("#movie_form");
  
  const reserve = jQuery("#reserve").click(openPopup).hide();

  jQuery("#submit_newsletter_page_2").click(openNewsletterPage3);

  const popup = jQuery("#popup").hide();

  const newsletter_page_1 = jQuery("#newsletter_page_1").hide();
  const newsletter_page_2 = jQuery("#newsletter_page_2").hide();
  const newsletter_page_3 = jQuery("#newsletter_page_3").hide();


  jQuery("#cities").hide();


  jQuery("#submit_newsletter_page_1").click(openNewsletterPage2);

  jQuery( ".movies_for_type" ).hide();
  
  jQuery( "#newsletter" ).click(openNewsletterPage1);

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

  function openNewsletterPage1(){

    newsletter_page_1.show();

  }

  function openNewsletterPage2(){

    newsletter_page_1.hide();
    newsletter_page_2.show();

    jQuery.ajax({
      method:'GET',
      url:'http://drupal.praksa/search/countries',
      success:function(response)
      {

       
        response["data"].forEach(element => {
          jQuery("#countries").append(jQuery(document.createElement('option')).prop({
            value:element.id,
            text: element.name
          }))
        })  

      }
      
    });

    jQuery('#countries').on('change', function() {
      if(jQuery('#countries').val() != 0){

        jQuery("#cities").empty()
        .append('<option selected="selected" value="0">Select a city</option>');
        
        jQuery.ajax({
          method:'GET',
          url:'http://drupal.praksa/search/cities/'+jQuery('#countries').val(),
          success:function(response)
          {
           
            response["data"].forEach(element => {
              jQuery("#cities").append(jQuery(document.createElement('option')).prop({
                value:element.name,
                text: element.name
              }))
            })  
    
          }
          
        })

        jQuery("#cities").show();
      }
    });

  }

  function openNewsletterPage3(){
    newsletter_page_2.hide();
    newsletter_page_3.show();
    jQuery("#save_subscription").on('click', function (){

      let data = '{';

      // "first_name":'+'"' + jQuery("#first_name").val() + '"'

      let fruits = ['first_name', 'last_name', 'gender','phone_number','email','countries','cities'];

      fruits.forEach((element,index) =>{
        if(index == 5){
          data+= '"' + element+'"'+ ':'+'"' + jQuery("#" + element +   " option:selected").text() + '"' + ','
        }
        else if(index == 6){
          data+= '"' + element+'"'+ ':'+'"' + jQuery("#" + element +   " option:selected").text() + '"'
        }else{
          data+= '"'+ element + '"' + ':'+'"' + jQuery("#" + element).val() + '"' + ','
        }
      });

      data+= '}';

      const parsedData = JSON.parse(data);

      if(!Object.values(parsedData).includes("") || !Object.values(parsedData).includes("Select a country:") || !Object.values(parsedData).includes("Select a city:")){

        if(parsedData.email === jQuery("#confirm_email").val()){
          jQuery.ajax({
            method:'GET',
            url:'http://drupal.praksa/save/subscriptions/'+data,
            success:function(response){
              alert(response["data"]);
            }
          });
        }
        else{
          alert("The emails do not match each other");
        }

      }
      else{
        alert("All required fields are not set");

      }
      
    });
  }

  jQuery("#newsletter_page_1_cancel").click(function(){
    newsletter_page_1.hide();
  });

  
  jQuery("#newsletter_page_2_cancel").click(function(){
    newsletter_page_2.hide();
  });

  jQuery("#newsletter_page_3_cancel").click(function(){
    newsletter_page_3.hide();
  });
