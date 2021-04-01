<?php

namespace Drupal\movie_reservation_controller\Controller;

class MovieReservationController {
    
    public function page(){

        $nids = \Drupal::entityQuery('node')->condition('type', 'movies')->execute();
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        return [
            '#theme' => 'article_list',
            '#items' => $nodes,
            '#title' => 'Movies testing'
        ];
    }
}