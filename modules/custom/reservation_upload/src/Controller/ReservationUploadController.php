<?php

namespace Drupal\reservation_upload\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Returns responses for reservation_upload routes.
 */
class ReservationUploadController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($customer_name,$movie) {

    $data = [
      "customer_name" => $customer_name,
      "movie" => $movie
    ];

    $movie_data = explode("|", $movie);

    $connection = \Drupal\Core\Database\Database::getConnection();
    $connection->insert('reservations')->fields([ 'day_of_reservation' => $movie_data[3],'time_of_reservation' => date('Y-m-d h:i:s'), 'reserved_movie_name' => $movie_data[1], 'reserved_movie_genre' => $movie_data[2], 'customer_name' => $customer_name ]) ->execute();

    return new JsonResponse([ 'data' => 'Reservation successfully saved to the database', 'method' => 'GET', 'status'=> 200]);

  }

}
