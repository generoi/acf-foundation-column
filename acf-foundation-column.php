<?php

/*
Plugin Name: Advanced Custom Fields: Foundation column settings
Plugin URI: genero.fi
Description: Adds an Advanced Custom Field field to setup foundation grid column classes.
Version: 1.0.0
Author: Genero
Author URI: genero.fi
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function include_field_types_foundation_column($version)
{
  include_once __DIR__ . '/acf-foundation-column-v5.php';
  include_once __DIR__ . '/acf-foundation-row-v5.php';
}

add_action('acf/include_field_types', 'include_field_types_foundation_column');
