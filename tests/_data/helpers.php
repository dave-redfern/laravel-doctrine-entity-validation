<?php
if (!defined('storage_path')) {
    function storage_path($path)
    {
        return realpath(__DIR__ . '/../_output') . '/' . $path;
    }
}
