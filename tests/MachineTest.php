<?php
require __DIR__ . "/../src/automata/NFA.php";
require __DIR__ . "/../src/automata/State.php";
require __DIR__ . "/BookingNFA.php";

doWalk(["instabook", "paid"]);
doWalk(["special_date", "approved"]);
doWalk(["special_date", "0days"]);
doWalk(["special_date", "approved", "deposited", "30days", "paid", "0days"]);

function doWalk( $events ){
    echo "=======================================\n";
    $bookingNFA = new BookingNFA();
    echo $bookingNFA->state;
    foreach ($events as $transition){
        echo $bookingNFA->walk([$transition]);
    }
    echo PHP_EOL;
    echo "=======================================\n";
}