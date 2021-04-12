<?php

namespace Drupal\movie_export_controller\Controller;

    class MovieExportController {


        public function page(){

            $items = [
                ['name' => 'Article one'],
                ['name' => 'Article two'],
                ['name' => 'Article three'],
            ];

            return [
               '#theme' => 'export_list',
               '#items' => $items,
               '#title' => 'Our movie export controller title' 
            ];
        }

    }