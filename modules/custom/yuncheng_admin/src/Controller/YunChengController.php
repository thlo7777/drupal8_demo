<?php

namespace Drupal\yuncheng_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DrupalKernelInterface;
use Drupal\Core\Url;
use \Drupal\node\Entity\Node;
use Drupal\devel\DevelDumperManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class YunChengController extends ControllerBase {

    public function ajax_handler( Request $request  ) {

        //\Drupal::logger('ajax_handler')->notice( 'json: <pre>@data</pre>', array('@data' => print_r($method, true)) );
        $method = $request->getMethod();
        if ($method == "POST") {
            $data = json_decode( $request->getContent(), TRUE );

            $search_tag = $data['search_tag'];

            $terms = [];
            if ( !empty($search_tag['shiti_laiyuan_zhenti_tag']) || !empty($search_tag['shiti_laiyuan_moni_tag']) ) {
                unset($search_tag['shiti_laiyuan_zhenti_tag']);
                unset($search_tag['shiti_laiyuan_moni_tag']);
            }

            foreach ($search_tag as $termid) {
                $terms[] = $termid;
            }
            $xiaowen_nodes = $this->getNodesByTaxonomyTermIds($terms);
            //$shiti_nodes = $this->getNodesByTaxonomyTermIds($terms);
            //\Drupal::logger('ajax_handler')->notice( 'xwnodes: <pre>@data</pre>', array('@data' => print_r($xiaowen_nodes, true)) );

            $query = \Drupal::entityQuery('node')
                ->condition('status', 1)
                ->condition('type', 'yuwen_shiti')
                ->condition('field_shitixiaowen.target_id', $xiaowen_nodes, "IN");
            $nids = $query->execute();
            //\Drupal::logger('ajax_handler')->notice( 'shiti: <pre>@data</pre>', array('@data' => print_r($nids, true)) );
            
            //foreach ( $nids as $nid ) {
            $node_shiti = Node::loadMultiple($nids);

            $display_nodes = [];
            foreach ($node_shiti as $item) {
                $neirong = $item->field_topic_material->entity->field_cailiao_neirong->getValue()[0]['value'];
                $display_nodes[$item->id()]['neirong'] = $this->cut_str($neirong, 50);
                
                //\Drupal::logger('ajax_handler')->notice( 'shiti: <pre>@data</pre>', array('@data' => print_r($topic_neirong, true)) );
            }
            //}
            $response['data'] = $display_nodes;

        } else {
            $response['data'] = 'hello';
        }


        return new JsonResponse( $response );


    }

    /*
    Utf-8、gb2312都支持的汉字截取函数
    cut_str(字符串, 截取长度, 开始长度, 编码);
    编码默认为 utf-8
    开始长度默认为 0
    */
    protected function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') {

        if($code == 'UTF-8')
        {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);

            if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
            return join('', array_slice($t_string[0], $start, $sublen));
        }
        else
        {
            $start = $start*2;
            $sublen = $sublen*2;
            $strlen = strlen($string);
            $tmpstr = '';

            for($i=0; $i< $strlen; $i++)
            {
                if($i>=$start && $i< ($start+$sublen))
                {
                    if(ord(substr($string, $i, 1))>129)
                    {
                        $tmpstr.= substr($string, $i, 2);
                    }
                    else
                    {
                        $tmpstr.= substr($string, $i, 1);
                    }
                }
                if(ord(substr($string, $i, 1))>129) $i++;
            }
            if(strlen($tmpstr)< $strlen ) $tmpstr.= "...";
            return $tmpstr;
        }

    }

    protected function getNodesByTaxonomyTermIds($termIds) {
        $termIds = (array) $termIds;

        if(empty($termIds)){
            return NULL;
        }

        $query = \Drupal::database()->select('taxonomy_index', 'ti');
        $query->fields('ti', array('nid'));
        $query->condition('ti.tid', $termIds, 'IN');
        $query->distinct(TRUE);
        $result = $query->execute();

        if($nodeIds = $result->fetchCol()){
            return $nodeIds;
            //return Node::loadMultiple($nodeIds);
        }

        return NULL;
    }

}
