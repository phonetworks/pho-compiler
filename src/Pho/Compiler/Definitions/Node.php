<?php

namespace Pho\Compiler\Definitions;

class Node {

    public $name;

    public $is_node = false;
    public $is_edge = false;

    public $is_actor = false;
    public $is_graph = false;
    public $is_object = false;

    public $is_read = false;
    public $is_write = false;
    public $is_subscribe = false;

    public $is_transmit = false;

    public $is_contain = false;

    public $mod; // Directive
    public $mask; // Directive

    public $fields; // array of Field objects



}