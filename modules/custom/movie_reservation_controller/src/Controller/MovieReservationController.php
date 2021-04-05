<?php

namespace Drupal\movie_reservation_controller\Controller;

class MovieReservationController {

    public function page(){

//        $nids = \Drupal::entityQuery('node')->condition('type', 'movies')->execute();
//        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);

        $vid = 'movie_type';
        /** @var \Drupal\taxonomy\Entity\Term[] $terms */
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => $vid]);

        return [
            '#theme' => 'article_list',
            '#items' => $terms,
            '#title' => 'Welcome to our movie reservation page'
        ];
    }
}
