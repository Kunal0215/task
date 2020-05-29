<?php
namespace Drupal\moviedb\Controller;

use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Controller\ControllerBase;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\moviedb\Plugin\Block\ListBlock as Block;


/**
  * This class contains functions for logical operations for various functions of site.
*/
class PhpController extends ControllerBase {

  /**
   * This function shows all movies at a page
   * @return mixed [Movie database details]
   */
  public function movie_list() {
    // Define content type for the data
    $bundle = 'movie_database';
    // Entity query
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', $bundle)
        ->sort('field_release_date', 'DESC');
    // Execute query
    $nids = $query->execute();
    // Check if any nids are not fetched from above query
    if (empty($nids)) {
      return array(
        '#markup' => 'No Movies Found',
        '#title' => 'Movies Database',
        '#items' => '',
      );
    }
    else {
      // Load nodes for fetching data from them
      $nodes = entity_load_multiple('node', $nids);
      foreach($nodes as $node) {
        $mid = $node->id();
        $node_title = $node->title->value;
        $node_body = $node->get('body')->value;
        $date = $node->get('field_release_date')->value;
        $ratings = $node->field_movie_rating->getValue();
        // Rating scale to out of 5
        $rating = $ratings[0]['rating'];
        $rating = $rating / 20;
        $rating = $rating . "/" . "5";
        // Image fetching from directory
        $node_image_fid = $node->get('field_poster')->target_id;
        if (!is_null($node_image_fid)) {
          $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
          $image_entity_url = $image_entity->url();
        }
        // No image fetched render warning image
        else {
          $image_entity_url = "/sites/default/files/excal.png";
        }
        // Get paragraph ids
        $target_id = array();
        $target_id = $node ->field_roles_of_actor->getValue();
        $actors = array();
        $j = 0;
        // Load all paragraphs for actor roles in a movie
        foreach ($target_id as $value) {
          $paragraph = Paragraph::load($value['target_id']);
          $actor_id = $paragraph->field_actor_a_name->target_id;
          $actor = Node::load($actor_id);
          // Actor name and url
          $actors[$j]['name'] = $actor->title->value;
          $actors[$j]['url'] = "/node/" . $actor_id;
          $j++;
        }
        // Returnable array for all data fetched
        $items[] = [
          'name' => $node_title,
          'url' => $image_entity_url,
          'desc' => $node_body,
          'date' => $date,
          'rating' => $rating,
          'cast' => $actors,
          'url_movie' => "/node/".$mid,
        ];
      }
      // Return statement
      return array(
        '#theme' => 'movie_list',
        '#items' => $items,
        '#title' => 'Movies Database',
      );
    }
  }

  /**
   * This function shows all actors at a page
   * @return mixed [Actor database details]
   */
  public function actor_list() {
    // Define content type for the data
    $bundle = 'actors';
    // Entity query
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', $bundle);
    // Execute query
    $nids = $query->execute();
    // No actor nid found error handling
    if (empty($nids)) {
      return array(
        '#markup' => 'No Actors Found',
        '#title' => 'Actors Database',
        '#items' => '',
      );
    }
    else {
      // Load all actor nids to fetch futher details
      $actor_nodes = entity_load_multiple('node', $nids);
      $items = array();
      $mids = array();
      // Each actor details loop
      foreach($actor_nodes as $node) {
        $nid = $node->id();
        $bundle = 'movie_database';
        // Entity query
        $query = \Drupal::entityQuery('node')
          ->condition('status', 1)
          ->condition('type', $bundle)
          ->condition('field_roles_of_actor.entity:paragraph.field_actor_a_name.target_id',$nid)
          ->sort('field_release_date', 'AESC');
        // Execute query
        $mids = $query->execute();
        // Fetch actor detials
        $node_title = $node->title->value;
        $node_body = $node->get('body')->value;
        $node_image_fid = $node->field_actor_image->target_id;
        // Check if image is given or not
        if ( !is_null($node_image_fid) ){
          $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
          $image_entity_url = $image_entity->url();
        }
        else{
          $image_entity_url = "/sites/default/files/excal.png";
        }
        // Check if actor has no movie in record
        if (empty($mids)) {
          $recent_movie = 'No movie found';
          $rating = 'NA';
          $date = 'NA';
          $movie_url = '';
        }
        else {
          // Fetch movie details to get latest
          foreach($mids as $value) {
            $node =  Node::load($value);
            $recent_movie = $node->title->value;
            $date = $node->get('field_release_date')->value;
            $ratings = $node->field_movie_rating->getValue();
            $rating = $ratings[0]['rating'];
            $rating = $rating/20;
            $rating = $rating." / "."5";
            $movie_url = "/node/".$value;
          }
        }
        // Store all fetched data to renderable array
        $items[] = [
          'name' => $node_title,
          'desc' => $node_body,
          'image' => $image_entity_url,
          'actor_url' => "/node/".$nid,
          'recent_movie' => $recent_movie,
          'recent_movie_date' => $date,
          'recent_movie_rating' => $rating,
          'movie_url' => $movie_url,
        ];
      }
    }
    // Return statement
    return array(
      '#theme' => 'actor_list',
      '#items' => $items,
      '#title' => 'Actors Database',
    );
  }

  /**
   * This function shows Actor's role in a specific movie in popup
   * @param   $movie [Movie Id]
   * @param   $nid   [Actor Id]
   * @return  mixed [Actor movie details]
   */
  public function costar($movie = NULL, $nid = NULL) {
    // Valudate URL values
    if (!is_null($movie) && !is_null($nid) && $movie != $nid) {
      // Load movienode
      $node = Node::load($movie);
      $check = $node->bundle();
      // To check if movie is loaded or movie has roles defined
      if (!is_null($check) || $check == 'movie_database') {
        // Get ids of all paragraphs in a movie
        $target_id = $node->field_roles_of_actor->getValue();
        foreach ($target_id as $value) {
          // Load paragraphs with case details
          $paragraph = Paragraph::load($value['target_id']);
          // fetch actor Id of a actor for vlaidation
          $actor_id = $paragraph->field_actor_a_name->target_id;
          if($actor_id == $nid) {
            $actor = Node::load($actor_id);
            // Role of actor
            $role = $paragraph->field_role_of_actor->value;
            // Image id to fetch image of actor
            $node_image_fid = $actor->field_actor_image->target_id;
            if ( !is_null($node_image_fid) ){
              $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
              $image_entity_url = $image_entity->url();
            }
            else{
              $image_entity_url = "/sites/default/files/excal.png";
            }
            // Actor name
            $node_title = $actor->title->value;
            // Returnable array
            $items = [
              'name' => $node_title,
              'image' => $image_entity_url,
              'role' => $role,
            ];
          }
        }
      }
      else {
        $items = [
          'name' => 'ERROR',
          'image' => '',
          'role' => 'Movie not given in Id',
        ];
      }
    }
    else {
      $items = [
        'name' => 'ERROR',
        'image' => '',
        'role' => 'Either NULL or same values given',
      ];
    }
    // Return data in JSON format
    return new JsonResponse($items);
  }

  /**
   * This function gives out search result for search bar
   * @return mixed [Search result of actor name or movie name]
   */
  public function form_movie() {
    // Build and redner form
    $form = $this->formBuilder()->getForm('Drupal\moviedb\Form\FilterByName');
    $form_rendered = \Drupal::service('renderer')->render($form);
    // Get value from query parameter
    $name = \Drupal::request()->query->get('word');
    // Query for content with value of query parameter
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('title', $name, 'CONTAINS');
    $nids = $query->execute();
    // kint($nids);
    if (empty($nids)) {
      return array(
        "#title" => "No Results Found",
        "#markup" => "Error 404, No Response Found"
      );
    }
    else {
      $nodes = entity_load_multiple('node', $nids);
      foreach($nodes as $node) {
        $mid = $node->id();
        $node_title = $node->title->value;
        $node_type = $node->bundle();
          $node_body = $node->get('body')->value;
          if ($node_type == 'movie_database') {
            $node_image_fid = $node->get('field_poster')->target_id;
            $type = 'Movie';
          }
          elseif($node_type == 'actors') {
            $node_image_fid = $node->get('field_actor_image')->target_id;
            $type = 'Actor';
          }
          if (!is_null($node_image_fid)) {
            $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
            $image_entity_url = $image_entity->url();
          }
          else {
            $image_entity_url = "/sites/default/files/excal.png";
          }
          $items[] = [
            'name' => $node_title,
            'image' => $image_entity_url,
            'desc' => $node_body,
            'url' => "/node/" . $mid,
            'type' => $type,
          ];
      }
      return array(
          '#theme' => 'search',
          '#items' => $items,
          '#title' => 'Search',
        );
    }
  }
}
