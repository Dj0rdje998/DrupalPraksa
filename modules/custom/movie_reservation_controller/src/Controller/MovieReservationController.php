<?php

namespace Drupal\movie_reservation_controller\Controller;


use \Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieReservationController {

    public function page(){

      $movie_genres =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'movie_type']);

      return [
          '#theme' => 'article_list',
          '#items' => $movie_genres,
          '#title' => 'Welcome to our movie reservation page'
      ];
        
    }

    public function filter($movie_type){

      $termIds = (array) $movie_type;
      if(empty($termIds)){
        return NULL;
      }

      $result = [];
      $query = \Drupal::database()->select('taxonomy_index', 'ti');
      $query->fields('ti', array('nid'));
      $query->condition('ti.tid', $termIds, 'IN');
      $query->distinct(TRUE);
      $nodeId = $query->execute();
    
      if($nodeIds = $nodeId->fetchCol()){
        $values =  \Drupal\node\Entity\Node::loadMultiple($nodeIds);

      }

      foreach ($values as $value) {
        
        
        $result[] = [
          "id" => $value->id(),
          "title" => $value->title->getString(),
          "genre" => $value->field_movie_type->getString(),
          "reservation_period" =>$value->field_reservation_period->getString()
        ];    
      }

      return new JsonResponse([ 'data' => $result, 'method' => 'GET', 'status'=> 200]);

    } 

    public function save($customer_name,$movie){
      
      $movies = json_decode($movie);

      $database = \Drupal::database();
      $query = $database->query('SELECT * FROM reservations 
        WHERE reserved_movie_name = :reserved_movie_name 
        AND day_of_reservation = :day_of_reservation', 
        [
        ':reserved_movie_name' => $movies->title,
        ':day_of_reservation' => $movies->reservation_period
      ]);
      $result = $query->fetchAll();

      $resultMovie = Node::load($movies->id);

      $days = explode(",",$resultMovie->field_reservation_period->getString());

      foreach ($days as $key => $day) {
        
        if(strcmp($day, $movies->reservation_period) == 0){
          if(count($result) >= (int)$resultMovie->field_number_of_attendants->value){
            unset($days[$key]);

            try {
              $resultMovie->set('field_reservation_period', implode(",", $days) );
              $resultMovie->save();
            }
            catch (\Exception $e) {
              $message = 'Something went wrong trying to upload reservation on MovieReservationController ';

              drupal_set_message($message, 'error');
         
              return FALSE;
            }

          }
          else {
            $connection = \Drupal\Core\Database\Database::getConnection();
            $connection->insert('reservations')
            ->fields([ 
            'day_of_reservation' => $movies->reservation_period,
            'time_of_reservation' => date('Y-m-d h:i:s'),
            'reserved_movie_name' => $movies->title, 
            'reserved_movie_genre' => $movies->genre,
            'customer_name' => $customer_name 
            ])
            ->execute();
          
            return new JsonResponse([ 'data' => 'Reservation successfully saved to the database', 'method' => 'GET', 'status'=> 200]);
            
          }

        }
      }
      

    }

    public function export() {

      return [
         '#theme' => 'export_list',
         '#title' => 'Our movie export controller title' 
      ];

  }

  public function import_book() {

    $response = \Drupal::httpClient()->request('GET', 'https://www.chilkatsoft.com/xml-samples/bookstore.xml')->getBody()->getContents();

    $responseXml = simplexml_load_string($response);

    $json_string = json_encode($responseXml);    

    $result_array = json_decode($json_string, TRUE);

    $books = $result_array["book"];

    foreach ($books as $book) {

      $node = Node::create([
        'type' => 'book',
        'title' => $book['title'],
        'field_comments' => $book['comments']['userComment'],
        'field_isbn' => $book['@attributes']['ISBN'],
        'field_price' => $book['price'],
      ]);
      
      $node->save();

    };

    $response = new Response();
    $response->setContent('Movies added successfully');

    return $response;

  }

  public function show_book() {

    $nids = \Drupal::entityQuery('node')->condition('type','book')->execute();
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);


    return [
      '#theme' => 'data_list',
      '#items' => $nodes,
      '#title' => 'Our book show controller title' 
    ];
  }

  public function load_movie_reservations_data($field, $order){


    $database = \Drupal::database();
    $query = $database->query('SELECT * FROM reservations ORDER BY '. $field .' ' . $order);

    $reservations = $query->fetchAll();

    return new JsonResponse([ 'data' => $reservations, 'method' => 'GET', 'status'=> 200]);
           

  }

  public function display_movie_reservations(){

    
    $response = \Drupal::httpClient()->request('GET', 'http://drupal.praksa/get-reservations-data')->getBody()->getContents();

    $movie_reservation_array = json_decode($response);

    return [
      '#theme' => 'reservation_list',
      "#items" => $movie_reservation_array->data,
      '#title' => 'These are the active movie reservations' 
    ];
  }

}
