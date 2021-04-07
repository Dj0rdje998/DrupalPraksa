<?php

namespace Drupal\movie_type_filter_controller\Controller;

use Drupal\taxonomy\Entity\Term;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieTypeFilterController {

    public function filter($movie_type){

        $termIds = (array) $movie_type;
        if(empty($termIds)){
          return NULL;
        }

        $rezultat = [];
        $query = \Drupal::database()->select('taxonomy_index', 'ti');
        $query->fields('ti', array('nid'));
        $query->condition('ti.tid', $termIds, 'IN');
        $query->distinct(TRUE);
        $result = $query->execute();
      
        if($nodeIds = $result->fetchCol()){
          $values =  \Drupal\node\Entity\Node::loadMultiple($nodeIds);

        }


        foreach ($values as $value) {
          
          
          $rezultat[] = [
            "id" => $value->id(),
            "title" => $value->title->getString(),
            "description" => $value->get('field_description')->value,
            "reservation_period" =>$value->field_reservation_period->getString()
          ];    
        }

        return new JsonResponse([ 'data' => $rezultat, 'method' => 'GET', 'status'=> 200]);
        
    }
}
