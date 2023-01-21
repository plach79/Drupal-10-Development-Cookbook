<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class MyPageController extends ControllerBase {

  /**
   * Returns markup for our custom page.
   *
   * @returns array
   *   The render array.
   */
  public function customPage(): array {
    return [
      '#markup' => '<p>Hello custom world!</p>'
    ];
  }

}
