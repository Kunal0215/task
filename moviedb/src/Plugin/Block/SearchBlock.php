<?php
namespace Drupal\moviedb\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'Search' Form as a block.
 *
 * @Block(
 *   id = "search_block",
 *   admin_label = @Translation("Search block"),
 *   category = @Translation("Search World"),
 * )
 */
class SearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Build and render the form for seach box
    $form = \Drupal::formBuilder()->getForm('Drupal\moviedb\Form\FilterByName');
    return $form;
  }
}
