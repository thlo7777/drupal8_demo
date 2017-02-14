<?php

namespace Drupal\dld_wxapp_cron\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A access Token worker.
 *
 * @QueueWorker(
 *   id = "cron_access_Token_queue",
 *   title = @Translation("wechat app access Token queue worker"),
 *   cron = {"time" = 30}
 * )
 */

class dld_wxapp_QueueToken extends QueueWorkerBase implements ContainerFactoryPluginInterface {
//class dld_wxapp_QueueToken extends QueueWorkerBase {
    /**
     * The logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * dld_wxapp_QueueToken constructor.
     *
     * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
     *   The logger service the instance should use.
     */
    //public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger) {
    public function __construct(LoggerChannelFactoryInterface $logger) {
        //parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    //public static function create(ContainerInterface $container) {

//        \Drupal::logger('dld_wxapp_QueueToken')->notice(
//            'configuration configuration: <pre>@configuration</pre>,
//             plugin_id: @plugin_id, plugin_definition: @plugin_definition',
//            array('@configuration' => $configuration,
//                '@plugin_id' => $plugin_id,
//                '@plugin_definition' => $plugin_definition
//            )
//        );
        return new static(
            $container->get('logger.factory')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function processItem($data) {

        $this->logger->get('dld_wxapp_QueueToken')->notice($data);
        $this->logger->get('dld_wxapp_QueueToken')->notice('my cron queue worker');
        //\Drupal::logger('dld_wxapp_QueueToken')->notice($data);

        // We access our configuration.
        //$cron_config = \Drupal::configFactory()->getEditable('examples.cron');
    }

}
