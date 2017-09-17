<?php

namespace Drupal\demo_hooks\Controller;

use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Controller for Hooks example description page.
 *
 * This class uses the DescriptionTemplateTrait to display text we put in the
 * templates/description.html.twig file.
 */
class DemoHooksController {
  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'demo_hooks';
  }

}
