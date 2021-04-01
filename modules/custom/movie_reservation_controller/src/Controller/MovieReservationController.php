<?php

namespace Drupal\movie_reservation_controller\Controller;

class MovieReservationController {
    
    public function page(){

        $items = array(
            array('name' => 'Name 1'),
            array('name' => 'Name 2'),
            array('name' => 'Name 3'),
        );

        $nids = \Drupal::entityQuery('node')->condition('type', 'movies')->execute();
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);


        // drupal_entity($nodes);

        // var_dump($items);


        return array(
            '#theme' => 'article_list',
            '#items' => $nodes,
            '#title' => 'Movies testing'
        );
    }
}