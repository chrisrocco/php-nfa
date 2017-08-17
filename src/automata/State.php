<?php

namespace tcc;


class State {
    public $id = null;
    public $edges = [];
    public $final = false;

    public function __construct($id, $final = false) {
        $this->id = $id;
        $this->final = $final;
    }

    /**
     * @param $transition
     * @return State new state
     */
    public function step($transition){
        if(!isset($this->edges[$transition])) return null;
        return $this->edges[$transition];
    }

    public function addEdge($transition, State $state){
        $this->edges[$transition] = $state;
    }

    public function id(){
        return $this->id;
    }

    public function __toString() {
        $out = "(".$this->id .")\n";
        foreach ($this->edges as $edge => $state){
            $out.="\t---".$edge."--->(".$state->id.")\n";
        }
        return $out;
    }
}