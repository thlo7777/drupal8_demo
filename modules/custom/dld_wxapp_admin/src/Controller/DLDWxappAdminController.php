<?php

namespace Drupal\dld_wxapp_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for dld_wxapp_admin.
 *
 * @ingroup dld_wxapp_admin
 */
class DLDWxappAdminController extends ControllerBase {

    /**
     * Drupal\Core\Entity\Query\QueryFactory definition.
     *
     * @var Drupal\Core\Entity\Query\QueryFactory
     */
    protected $entityQuery;


    protected $entityManager;

    public function __construct(QueryFactory $entityQuery, EntityManagerInterface $entityManager) {
    //public function __construct(EntityManagerInterface $entity_manager) {
        $this->entityQuery = $entityQuery;
        $this->entityManager = $entityManager;
    }

    public static function create(ContainerInterface $container) {
        return new static(
            $container->get('entity.query'),
            $container->get('entity.manager')
        );
    }

    /**
     * A simple controller method to explain what this module is about.
     */
    public function description() {
        $build['description'] = [
            '#markup' => '<p>Page for admin</p>',
        ];

        $query = $this->entityQuery->get('node')
                      ->condition('type', 'dld_wechat_api_row_record')
                      ->condition('title', 'get access token');

        $nids = $query->execute();
        //ksm(array_values($nids));

        foreach ($nids as $nid) {

            //$node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
            $node = $this->entityManager->getStorage('node')->load($nid);
            //ksm(array_values($node->field_api_record->getValue()));
            $build['request'] = [
                '#markup' => $node->field_wechat_api_url_request->value
            ];
            //ksm(array_values($node->field_wechat_api_url_request->getValue()));
        }


        return $build;
    }

}
