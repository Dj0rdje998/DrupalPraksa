<?php

namespace Drupal\book_show\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for book_show routes.
 */
class BookShowController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $nids = \Drupal::entityQuery('node')->condition('type','book')->execute();
    $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);


    return [
      '#theme' => 'data_list',
      '#items' => $nodes,
      '#title' => 'Our book show controller title' 
    ];
  }
}
