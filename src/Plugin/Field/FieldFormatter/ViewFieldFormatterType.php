<?php

/**
 * @file
 * Contains \Drupal\rendered_view_field\Plugin\Field\FieldFormatter\ViewFieldFormatterType.
 */

namespace Drupal\rendered_view_field\Plugin\Field\FieldFormatter;

use Drupal\block\Entity\Block;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'view_field_formatter_type' formatter.
 *
 * @FieldFormatter(
 *   id = "view_field_formatter_type",
 *   label = @Translation("View field formatter type"),
 *   field_types = {
 *     "view_field_type"
 *   }
 * )
 */
class ViewFieldFormatterType extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(// Implement default settings.
    ) + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return array(// Implement settings form.
    ) + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = $this->viewValue($item);
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    $viewId = $item->value;
    list($viewId, $displayId) = explode(':', $viewId);

    /** @var ViewExecutable $view */
    $view = Views::getView($viewId);

    /*
     * Calling preview for the view so that ajax gets attached because
     * $view->setAjaxEnabled(TRUE); is buggy. Remove this in the future
     */
    $renderedView = $view->preview($displayId);
    $renderedView['#cache']['tags'][] = 'node_list';
    return $renderedView;
  }

}
