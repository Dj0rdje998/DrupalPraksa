//   let movie_form = $("#movie_form");

//   let movies_for_type = $( ".movies_for_type" ).hide();

//   let popup = $("#popup").hide();
  
//   let reserve = $("#reserve").hide();

//   let table = document.getElementById('di-locations');

//   $( "#submit" ).click(function(e) {
//     e.preventDefault();

//     let movie_type = $("#movie_type").val();

//     $.ajax({
//       method:'GET',
//       url:'http://drupal.praksa/movie/type/'+movie_type,
//       success:function(response)
//       {
//        let resData = response["data"];

//        movie_form.html("");
//        reserve.hide();

//        resData.forEach(response => {

//         $('<input />', { type: 'checkbox', id: 'cb' + response.id,  name:"myCheck"}).val( response.id + "|" + response.title + "|"+ response.reservation_period)
//         .on("click",myFunction).appendTo(movie_form);
        
//         $('<label />', { 'for': 'cb' + response.id, text: response.title }).appendTo(movie_form);

//         $('<br/>').appendTo(movie_form);
//       });

//       }
      
//     });

//    movies_for_type.show();

   

//   });

//   function myFunction(){

//     let checkboxes = $("input[type='checkbox']")

//     if(checkboxes.is(":checked")){
//       reserve.show();
//     }
//     else{
//       reserve.hide();
//     }

//   }

//   function openPopup(){

//     popup.show();
    

//     var array = $.map($('input[name="myCheck"]:checked'), function(c){
      
//       var res = c.value.split("|");

//       return {
//         id : res[0],
//         title : res[1],
//         reservation_period : res[2],
//       };

//       });


//      $('#popup')
//       .append(
//         $(document.createElement('label')).prop({
//           for: 'movies'
//         }).html('Choose your reservation date: ')
//       )
//       .append(
//         $(document.createElement('select')).prop({
//           id: 'movies',
//         })
//       )
 
//     array.forEach(function (arrayItem) {

//       let days =arrayItem.reservation_period.split(",");

//        days.forEach(element => {
//         $("#movies").append($(document.createElement('option')).prop({
//           //Dodati i vreme koje je izabrano u value
//           value: arrayItem.id,
//           text: arrayItem.title + " " + element
//         }))
//       })  

//     });

//   }

//   function cancelPopup(){
//         popup.hide();

//     }


