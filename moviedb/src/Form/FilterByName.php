<?php
namespace Drupal\moviedb\Form;
/**
 * @file
 * This file contains form to make a seach bar
 */
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
/**
 * Class to make a form for seach
 */
class FilterByName extends FormBase {

  /**
     * This function is used to return formid.
     * @return formid
  */
  public function getFormId() {
    return 'resume_form';
  }
  /**
     * This function is used to return $form object.
     * @return mixed
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['search'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }
  /**
     * This function is used to redirect after submission to searchlist page.
     * @param  $form array ,$form_state Default parameters for form submission.
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // To get the input work in search field
    $input = $form_state->getUserInput();
    $key = $input['search'];
    // Reditect to URL for route of class with the search key ot be passed to function
    $url = Url::fromRoute('moviedb_formmovie', [], ['query' => ['word' => $key]]);
    // Redirect after submission
    $form_state->setRedirectUrl($url);
  }
}
?>
