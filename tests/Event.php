<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/17/2017
 * Time: 9:49 AM
 */

namespace tcc;


class Event implements StatefulEntity {

    public $state = "created";
    public $host = "Joe Bob";

    /**
     * @return string state_id
     */
    public function getState() {
        return $this->state;
    }

    public function setState($state_id) {
        $this->state = $state_id;
    }

    /**
     * @return StatefulContext context
     */
    public function getContext() {
        return new EventContext(new \BookingNFA(), $this);
    }
}