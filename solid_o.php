<?php

class SomeObject {
    protected $name;

    public function __construct(string $name) { }

    public function getObjectName() { }
}

class SomeObjectsHandler {
    public function __construct() { }

    public function handleObjects(array $objects): array {
        $handlers = [];
        foreach ($objects as $object) {
            $object_name = $object->getObjectName(); // 'object_1', 'object_2'
            $handlers[] = 'handle_' . $object_name;
        }

        return $handlers;
    }
}

$objects = [
    new SomeObject('object_1'),
    new SomeObject('object_2')
];

$soh = new SomeObjectsHandler();
$soh->handleObjects($objects);