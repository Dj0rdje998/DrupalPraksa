<?php

namespace Drupal\movie_reservation_controller\Controller;

class MovieReservationController {
    
    public function page(){

        $items = array(
            array('name' => 'Movie one'),
            array('name' => 'Movie two'),
            array('name' => 'Movie three'),
            array('name' => 'Movie four'),
            array('name' => 'Movie five'),
        );


        return array(
            '#theme' => 'article_list',
            '#items' => $items,
            '#title' => 'Movie list'
        );
    }
}