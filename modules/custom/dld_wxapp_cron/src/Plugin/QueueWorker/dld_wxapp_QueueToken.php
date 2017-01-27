<?php

namespace Drupal\dld_wxapp_cron\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * A access Token worker.
 *
 * @QueueWorker(
 *   id = "cron_access_Token_queue",
 *   title = @Translation("wechat app access Token queue worker"),
 *   cron = {"time" = 30}
 * )
 */

class dld_wxapp_QueueToken extends QueueWorkerBase {

    /**
     * {@inheritdoc}
     */
    public function processItem($data) {

        \Drupal::logger('dld_wxapp_QueueToken')->notice($data);
        \Drupal::logger('dld_wxapp_QueueToken')->notice('my cron queue worker');

        // We access our configuration.
        //$cron_config = \Drupal::configFactory()->getEditable('examples.cron');
    }

}
