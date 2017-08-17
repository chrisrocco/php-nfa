<?php
namespace tcc;

abstract class Action {
    public $transition;

    /**
     * @param \Closure $resolve callback on action complete
     * @param \Closure $reject callback on action failed
     * @return void
     */
    abstract function execute(\Closure $resolve, \Closure $reject);

    /**
     * @return string transition_id
     */
    abstract function transition();
}