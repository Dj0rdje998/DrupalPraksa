<?php

namespace Drupal\movie_reservation_controller\Controller;

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
}
