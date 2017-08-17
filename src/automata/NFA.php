<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/16/2017
 * Time: 11:55 PM
 */

namespace tcc;


class NFA {
    /**
     * @var State
     */
    public $state;
    /**
     * @var State[]
     */
    public $state_map;

    public function walk($edges){
        for($i = 0; $i < count($edges); $i++){
            $edge = $edges[$i];
            $new = $this->state->step($edge);
            if($new === null) Throw new \Exception("Illegal State Transition: ".$this->state->id." => ".$edge);
            $this->state = $new;
        }
        return $this->state;
    }

    /**
     * @return State current state
     */
    public function getState(){
        return $this->state;
    }

    public function setState($state_id){
        $this->state = $this->state_map[$state_id];
    }

    protected function addState(State $state){
        $this->state_map[$state->id] = $state;
    }

    protected function addEdge($state_id_from, $state_id_to, $transition_id){
        $from = $this->state_map[$state_id_from];
        $to = $this->state_map[$state_id_to];
        $from->addEdge($transition_id, $to);
    }

    public function __toString() {
        return (string) $this->state;
    }
}