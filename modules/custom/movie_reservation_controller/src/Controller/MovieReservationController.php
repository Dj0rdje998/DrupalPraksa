<?php

namespace Drupal\movie_reservation_controller\Controller;


use \Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieReservationController {

    public function page(){

        $vid = 'movie_type';
       
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => $vid]);

        return [
            '#theme' => 'article_list',
            '#items' => $terms,
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

          $movie_data = explode("|", $movie);
      
          $connection = \Drupal\Core\Database\Database::getConnection();
          $connection->insert('reservations')->fields([ 'day_of_reservation' => $movie_data[3],'time_of_reservation' => date('Y-m-d h:i:s'), 'reserved_movie_name' => $movie_data[1], 'reserved_movie_genre' => $movie_data[2], 'customer_name' => $customer_name ]) ->execute();

          return new JsonResponse([ 'data' => 'Reservation successfully saved to the database', 'method' => 'GET', 'status'=> 200]);
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

}
