<?php
require __DIR__ . "/../src/automata/NFA.php";
require __DIR__ . "/../src/automata/State.php";
require __DIR__ . "/../src/executors/Action.php";
require __DIR__ . "/../src/executors/StatefulContext.php";
require __DIR__ . "/../src/executors/StatefulEntity.php";
require __DIR__ . "/BookingNFA.php";
require __DIR__ . "/Event.php";
require __DIR__ . "/EventContext.php";

$event = new \tcc\Event();

$paymentAction = function($resolve, $reject){
    function swipeCard(){
        return true;
    }

    if(swipeCard()){
        $resolve("paid");
        return;
    }
    $reject();
};

$context = $event->getContext();
$context->trigger("instabook");
try {
    $context->attempt($paymentAction);
} catch (Exception $e){
    echo "Payment Failed".PHP_EOL;
}

echo $context->getEntity()->getState(); // echos 'booked'