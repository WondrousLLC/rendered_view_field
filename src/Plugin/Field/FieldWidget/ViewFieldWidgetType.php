<?php

/**
 * @file
 * Contains \Drupal\rendered_view_field\Plugin\Field\FieldWidget\ViewFieldWidgetType.
 */

namespace Drupal\rendered_view_field\Plugin\Field\FieldWidget;

use Drupal\Core\Config\Config;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display\DefaultDisplay;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Plugin implementation of the 'view_field_widget_type' widget.
 *
 * @FieldWidget(
 *   id = "view_field_widget_type",
 *   label = @Translation("View field widget type"),
 *   field_types = {
 *     "view_field_type"
 *   }
 * )
 */
class ViewFieldWidgetType extends WidgetBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $configService = \Drupal::service('config.storage');
    $selectedViews = $configService->read('rendered_view_field.viewsforviewfield')['selected_views'];
    $viewsToDisplayInSelect = [];

    foreach ($selectedViews as $option) {
      if (!$option) {
        continue;
      }

      list($viewId, $displayId) = explode(':', $option);

      /** @var ViewExecutable $view */
      $view = Views::getView($viewId)->storage;
      /** @var DefaultDisplay $block */
      $displays = $view->get('display');

      if (empty($displays[$displayId])) {
        continue;
      }

      $title = '[' . $view->get('label') . '] ' . $displays[$displayId]['display_title'];
      $viewsToDisplayInSelect[$option] = $title;
    }

    $element['value'] = [
        '#type' => 'select',
        '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
        '#options' => ['' => ''] + $viewsToDisplayInSelect
    ];

    return $element;
  }

}
