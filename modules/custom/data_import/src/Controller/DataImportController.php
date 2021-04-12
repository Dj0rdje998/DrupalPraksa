<?php

namespace Drupal\data_import\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use \Drupal\node\Entity\Node;


/**
 * Returns responses for data_import routes.
 */
class DataImportController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

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

}