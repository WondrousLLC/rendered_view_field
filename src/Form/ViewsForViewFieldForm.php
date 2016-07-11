<?php

/**
 * @file
 * Contains \Drupal\rendered_view_field\Form\ViewsForViewFieldForm.
 */

namespace Drupal\rendered_view_field\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Views;

/**
 * Class ViewsForViewFieldForm.
 *
 * @package Drupal\rendered_view_field\Form
 */
class ViewsForViewFieldForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'rendered_view_field.viewsforviewfield'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'views_for_view_field_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $views = Views::getEnabledViews();

    $viewOptions = [];

    foreach ($views as $view) {
      $displays = $view->get('display');

      foreach ($displays as $display) {
        if ($display['display_plugin'] !== 'block') {
          continue;
        }

        $id = $view->get('id') . ':' . $display['id'];
        $title = '[' . $view->get('label') . '] ' . $display['display_title'];
        $viewOptions[$id] = $title;
      }
    }

    $config = $this->config('rendered_view_field.viewsforviewfield');

    $form['selected_views'] = array(
      '#type' => 'checkboxes',
      '#title' => 'Select the views which should be renderable via the field',
      '#options' => $viewOptions,
      '#default_value' => $config->get('selected_views')
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('rendered_view_field.viewsforviewfield')
      ->set('selected_views', $form_state->getValue('selected_views'))
      ->save();
  }

}
