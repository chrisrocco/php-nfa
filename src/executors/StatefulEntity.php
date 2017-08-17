<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/17/2017
 * Time: 9:33 AM
 */

namespace tcc;


interface StatefulEntity {
    /**
     * @return string state_id
     */
    public function getState();


    public function setState($state_id);

    /**
     * @return StatefulContext context
     */
    public function getContext();
}