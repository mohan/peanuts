<?php
// Peanuts
// License: GPL

require_once './lib/csvdb.php';
require_once './lib/helpers.php';
require_once './app/data.php';
require_once './app/functions.php';


// filters
filter_set_config();
filter_set_username();
filter_set_flash();

// data
data_init();

// Routes
routes();
