A practical NFA Implementation
===
### For managing stateful entites

---
#### Getting Started
1. NFA, States, and Transitions
2. Stateful Entites, Stateful Context
3. Actions

##### NFA, States, and Transitions
NFA (Nondeterministic Finite Automata) is a concept in formal language theory.

We use it to define the entire possible lifecycle of an entity.

An NFA consists of **States** and **Transitions**. It is always in a **State**, and each
state may consume 0 or more **Transitions**.

```php
class BookingNFA extends \tcc\NFA {

    // PRO TIP: Draw this on paper before coding
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
```

To use this NFA, we simply call `walk(..)` with a number of transitions

```php
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
```

#### Stateful Entities and Their Context
A stateful entity is just a thing with a fixed number of possible states, and a life cycle
( or way of transitioning ) between those states.

Stateful entities implement `Stateful Entity`
```php
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
```

A **Stateful Context** provides a concrete way to interact with a **StatefulEntity**

It contains an NFA and the Entity itself. The Entity should be able to resolve its context. 

See its API for more details.

#### Actions
An optional abstraction for triggering transitions under a **Context** is with **Actions**

An actions (or executor) is simply a closure accepting two callback methods: `resolve, reject`.
It should always resolve with a **transition_id**

A context can invoke an action `attempt(Closure $action)`
```php
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
```