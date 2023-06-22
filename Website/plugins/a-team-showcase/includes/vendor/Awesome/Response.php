<?php
/**
 * Created by PhpStorm.
 * User: whitesunset
 * Date: 03.07.15
 * Time: 19:42
 */

class Awesome_Response {
    public static function get(WP_Ajax_Response $response, $data, $type){
        if($type == 'errors'){
            $response->add(array(
                    'what' => $type,
                    'id' => $data
                ));
        }else{
            $response->add(array(
                    'what'=> $type,
                    'action'=> $type,
                    'id'=>'1',
                    'data'=> $data,
                    'supplemental' => ''
                ));
        }
        return $response;
    }

} 