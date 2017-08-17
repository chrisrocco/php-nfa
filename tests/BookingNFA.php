<?php

class BookingNFA extends \tcc\NFA {

    public function __construct() {
        /* states */
        $this->addState(new \tcc\State("created"));
        $this->addState(new \tcc\State("pending_approval"));
        $this->addState(new \tcc\State("pending_payment"));
        $this->addState(new \tcc\State("booked_pending"));
        $this->addState(new \tcc\State("booked"));
        $this->addState(new \tcc\State("past", true));
        $this->addState(new \tcc\State("overdue"));
        $this->addState(new \tcc\State("terminated", true));

        /* transitions */
        $this->addEdge("created", "pending_approval", "special_date");
        $this->addEdge("created", "pending_payment", "instabook");
        $this->addEdge("pending_approval", "pending_payment", "approved");
        $this->addEdge("pending_approval", "terminated", "0days");
        $this->addEdge("pending_payment", "booked_pending", "deposited");
        $this->addEdge("pending_payment", "booked", "paid");
        $this->addEdge("pending_payment", "terminated", "0days");
        $this->addEdge("booked_pending", "booked", "paid");
        $this->addEdge("booked_pending", "overdue", "30days");
        $this->addEdge("overdue", "booked", "paid");
        $this->addEdge("booked", "past", "0days");
        $this->addEdge("booked_pending", "terminated", "0days");
        $this->addEdge("overdue", "terminated", "0days");

        /* start state */
        $this->setState("created");
    }

}