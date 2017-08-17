<?php
/**
 * Created by PhpStorm.
 * User: chris
 * Date: 8/17/2017
 * Time: 9:34 AM
 */

namespace tcc;


class StatefulContext {

    protected $nfa;
    protected $statefulEntity;

    public function __construct(NFA $nfa, StatefulEntity $statefulEntity) {
        $this->nfa = $nfa;
        $this->statefulEntity = $statefulEntity;
    }

    public function attempt(\Closure $action){
        $action(function($transition_id){
            $this->trigger($transition_id);
        }, function(){
            throw new \Exception("Action Failed");
        });
    }

    public function trigger($transition_id){
        $steps = [$transition_id];
        if(is_array($transition_id)) $steps = $transition_id;
        $new = $this->nfa->walk($steps);
        if(!$new) return;
        $this->statefulEntity->setState($new->id());
    }

    /**
     * @return StatefulEntity
     */
    public function getEntity(){
        return $this->statefulEntity;
    }
}