<?php
class CI_Controller { /** @var CI_DB_query_builder $db */ public $db; }
function get_instance() { return new CI_Controller(); }