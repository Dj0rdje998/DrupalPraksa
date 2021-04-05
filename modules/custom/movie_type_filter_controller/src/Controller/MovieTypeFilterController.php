<?php

namespace Drupal\movie_type_filter_controller\Controller;

use Drupal\taxonomy\Entity\Term;

class MovieTypeFilterController {

    public function filter($movie_type){

    //    $nids = \Drupal::entityQuery('node')->condition('type', 'movies')->execute();
    //    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);


    //     $term = Term::load($movie_type);
    //     $name = $term->getName();

        

        $termIds = (array) $movie_type;
        if(empty($termIds)){
          return NULL;
        }
      
        $query = \Drupal::database()->select('taxonomy_index', 'ti');
        $query->fields('ti', array('nid'));
        $query->condition('ti.tid', $termIds, 'IN');
        $query->distinct(TRUE);
        $result = $query->execute();
      
        if($nodeIds = $result->fetchCol()){
          $values =  \Drupal\node\Entity\Node::loadMultiple($nodeIds);

        }
      



        return [
            '#theme' => 'movie-list',
            '#items' => $values,
            '#title' => 'We have thease movies available'
        ];
    }
}
