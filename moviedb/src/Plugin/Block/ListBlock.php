<?php
namespace Drupal\moviedb\Plugin\Block;
/**
 * This file is used for rednering actor and movie specific on own node pages
 * rathr then new page of movielist or actor list
 */
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing;
use Drupal\moviedb\Controller as Controller;

/**
 * Provides a 'List' Block.
 *
 * @Block(
 *   id = "list_block",
 *   admin_label = @Translation("List block"),
 *   category = @Translation("List World"),
 * )
 */
class ListBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  /**
   * Block to render a actor on its own node page ather then making a new page for actor lisy
   * @param  [int] $nid [actorid]
   * @return [mixed]
   */
  public function actor_movielist($nid) {
    // Load actor node
    $node_details = Node::load($nid);
    // Fetch basic actor details required
    $actor_name = $node_details->title->value;
    $actor_test = $node_details->type->target_id;
    $actor_image = $node_details->get('field_actor_image')->target_id;
    // Check for any image uploaded or not and if not load a default error image
    if (!is_null($actor_image)) {
      $image_entity = \Drupal\file\Entity\File::load($actor_image);
      $actor_image_url = $image_entity->url();
    }
    else {
      $actor_image_url = "/sites/default/files/excal.png";
    }
    // To return out actor details to twig file for rendering
    $actordata[] =[
      'actorname' => $actor_name,
      'actorimage' => $actor_image_url,
    ];
    // Fetch out actor's movies
    $bundle = 'movie_database';
    $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', $bundle)
        ->condition('field_roles_of_actor.entity:paragraph.field_actor_a_name.target_id',$nid)
        ->sort('field_release_date', 'DESC');
    $m_ids = $query->execute();
    // No movies done by actor to show
    if(empty($m_ids)) {
      $data = array("#markup" => "No Results Found");
    }
    else {
      // Load up movies done by actor
      $nodes = entity_load_multiple('node', $m_ids);
      $items = array();
      $actor = array();
      // For each individual movie get details like Id, name, desc, image, rating  and costars
      foreach($nodes as $node) {
        $mid = $node->id();
        $node_title = $node->title->value;
        $node_body = $node->get('body')->value;
        $date = $node->get('field_release_date')->value;
        $ratings = $node->field_movie_rating->getValue();
        // Ratings are stored as percetage values had to conver to 5 star ratings
        $rating = $ratings[0]['rating'];
        $rating = $rating/20;
        $rating = $rating."/"."5";
        // Check for image upload and if not show defualt image
        $node_image_fid = $node->get('field_poster')->target_id;
        if (!is_null($node_image_fid)) {
          $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
          $image_entity_url = $image_entity->url();
        }
        else {
          $image_entity_url = "/sites/default/files/excal.png";
        }
        // Load paragraph to get actor's costars and their role for each movie
        $target_id = array();
        $target_id = $node ->field_roles_of_actor->getValue();
        $coactors = array();
        $j = 0;
        foreach ($target_id as $value) {

          $paragraph = Paragraph::load($value['target_id']);
          $costar_id = $paragraph->field_actor_a_name->target_id;
          // Fetch the costar id to pass to costar function to load image of the costar form his profile
          $actorname = Node::load($costar_id);
          // Added a counter 'j' for details   of multiple actor in a movie with the current actor
          if($actorname->title->value != $actor_name) {
            $coactors[$j]['name'] = $actorname->title->value;
            $coactors[$j]['id'] = $costar_id;
            $j++;
          }
          else {
            $actor_role = $paragraph->field_role_of_actor->value;
          }
        }
        // Returnable array
        $items[] = [
          'moviename' => $node_title,
          'image' => $image_entity_url,
          'desc' => $node_body,
          'date' => $date,
          'role' => $actor_role,
          'cast' => $coactors,
          'movie_id' => $mid,
        ];
      }
      return array(
        'theme' => 'actor_movielist',
        'items' => $items,
        'actordata' => $actordata,
        'title' => "Details of " . $actor_name,
      );
    }
  }

  /**
   * Block to render a movie on its own node page ather then making a new page for movie lisy
   * @param  [int] $node [movie id]
   * @return [mixed]
   */
  public function moviedetails($node) {
    // Load movie node and fetch basic details like title and descriptions
    $bundle = 'movie_database';
    $node_details = Node::load($node);
    $title = $node_details->title->value;
    $node_body = $node_details->get('body')->value;
    $date = $node_details->get('field_release_date')->value;
    // Ratings are stored as percetage values had to conver to 5 star ratings
    $ratings = $node_details->field_movie_rating->getValue();
    $rating = $ratings[0]['rating'];
    $rating = $rating/20;
    $rating = $rating."/"."5";
    // Check for image upload and if not show defualt image
    $node_image_fid = $node_details->get('field_poster')->target_id;
    if (!is_null($node_image_fid)) {
      $image_entity = \Drupal\file\Entity\File::load($node_image_fid);
      $image_entity_url = $image_entity->url();
    }
    else {
      $image_entity_url = "/sites/default/files/excal.png";
    }
    // Load paragraphs to get details of actors working on that movie
    $target_id = array();
    $target_id = $node_details ->field_roles_of_actor->getValue();
    $actors = array();
    $j = 0;
    foreach ($target_id as $value) {
      // Load paragraphs on based on target value of vlaues
      $paragraph = Paragraph::load($value['target_id']);
      // Fetch actor name and role in that movie
      $actor_id = $paragraph->field_actor_a_name->target_id;
      $actor = Node::load($actor_id);
      $actors[$j]['name'] = $actor->title->value;
      $actors[$j]['role'] = $paragraph->field_role_of_actor->value;
      // Url to be linable to actor page
      $actors[$j]['url'] = "/node/" . $actor_id;
      $j++;
    }
    // Returnable array
    $items[] = [
      'image_url' => $image_entity_url,
      'desc' => $node_body,
      'date' => $date,
      'rating' => $rating,
      'cast' => $actors,
    ];
    return array(
      'theme' => 'moviedetails',
      'items' => $items,
      'title' => $title,
    );
  }

  /**
   * [build up the block for rendering data of above functions]
   * @return [mixed] [description]
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid  = $node -> id();
    $data = array();
    $node_details = Node::load($nid);
    $actor_test = $node_details->type->target_id;
    // If node is of actor type then redner actor details otherwise movie details
    // For being more dynamic
    if ($actor_test == 'actors') {
      $data = $this->actor_movielist($nid);
      return  array(
        '#theme' => $data['theme'],
        '#items' => $data['items'],
        '#title' => $data['title'],
        '#actordata' => $data['actordata'],
        '#cache' => [
          'max-age' => 0,
        ]
      );
    }
    elseif($actor_test == 'movie_database') {
      $data = $this->moviedetails($nid);
      return  array(
        '#theme' => $data['theme'],
        '#items' => $data['items'],
        '#title' => $data['title'],
        '#cache' => [
          'max-age' => 0,
        ]
      );
    }
  }
}
