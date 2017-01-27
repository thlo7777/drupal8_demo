<?php

namespace Drupal\dld_wxapp_request\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Serialization\Json;


/**
 * Controller routines for dld_wxapp_admin.
 *
 * @ingroup dld_wxapp_admin
 */
class DLDWxappFrontController extends ControllerBase {

    /**
    * A simple controller method to explain what this module is about.
    */
    public function dispatch(Request $request) {


        $header = $request->headers->get('Content-Type');
        $this->getLogger('dld_wxapp_request')->notice($header);

        if ($header == 'application/json') {
            $method = $request->getMethod();
            $this->getLogger('dld_wxapp_request')->notice('method:' . $method);

            if ($method == 'POST') {
                $content = $request->getContent();
                $params = json_decode($content, TRUE);
                if (!empty($content)) {

                    $this->getLogger('dld_wxapp_request')->notice(
                        'content = <pre>@data</pre>', 
                        array('@data' => print_r($params, TRUE))
                    );
                }
            }
        }

        if ($header == 'application/x-www-form-urlencoded;charset=UTF-8') {
            $count = $request->request->get('count');
            if (!$count) {
                $this->getLogger('dld_wxapp_request')->notice('count is null');
            }
            //$this->getLogger('dld_wxapp_request')->notice('all:' . $openid);

            $uri = $request->getQueryString();
            $this->getLogger('dld_wxapp_request')->notice('uri:' . $uri);
        }

//        $method = $request->getMethod();
//        $this->getLogger('dld_wxapp_request')->notice('method:' . $method);
//
//        if ($method == 'POST') {
//            $raw_data = file_get_contents("php://input"); //get xml data
//            $params = json_decode($raw_data, TRUE);
//            $this->getLogger('dld_wxapp_request')->notice(
//                'raw_data = <pre>@data</pre>', 
//                array('@data' => print_r($params, TRUE))
//            );
//        }



        $response['message'] = 'ok';
        $response['count'] = '1';

        return new JsonResponse( $response );
    }

}
