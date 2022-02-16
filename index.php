<?php
// License: GPL

require_once './lib/csvdb-core.php';
require_once './lib/helpers.php';
require_once './lib/functions.php';


// filters
filter_set_config();
filter_set_username();
filter_set_flash();

// csvdb
csvdb_init();

// Routes
routes();
