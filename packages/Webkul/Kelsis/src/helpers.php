<?php

if (!function_exists('kelsis_path')) {
  function kelsis_path($path = '') {
    return base_path('packages/Webkul/Kelsis/' . $path);
  }
}

?>