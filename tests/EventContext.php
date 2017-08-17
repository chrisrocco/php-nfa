<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/17/2017
 * Time: 12:40 AM
 */

namespace tcc;


class EventContext extends StatefulContext {

    public function __construct(\BookingNFA $nfa, Event $event) {
        parent::__construct($nfa, $event);
    }

}