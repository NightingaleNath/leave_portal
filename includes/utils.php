<?php
date_default_timezone_set('Africa/Accra');
function calculate_time_ago($date_created) {
    $now = time();
    $created_at = strtotime($date_created);
    $time_ago = $now - $created_at;
    if ($time_ago < 60) { // Less than 1 minute ago
        $time_unit = $time_ago == 1 ? 'sec' : 'secs';
        $due_label = '<strong class="label label-danger">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    } elseif ($time_ago < 3600) { // Less than 1 hour ago
        $time_ago = floor($time_ago / 60); // Convert to minutes
        $time_unit = $time_ago == 1 ? 'min' : 'mins';
        $due_label = '<strong class="label label-danger">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    } elseif ($time_ago < 86400) { // Less than 1 day ago
        $time_ago = floor($time_ago / 3600); // Convert to hours
        $time_unit = $time_ago == 1 ? 'hr' : 'hrs';
        $due_label = '<strong class="label label-danger">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    } elseif ($time_ago < 604800) { // Less than 1 week ago
        $time_ago = floor($time_ago / 86400); // Convert to days
        $time_unit = $time_ago == 1 ? 'day' : 'days';
        $due_label = '<strong class="label label-warning">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    } elseif ($time_ago < 2592000) { // Less than 1 month ago
        $time_ago = floor($time_ago / 604800); // Convert to weeks
        $time_unit = $time_ago == 1 ? 'week' : 'weeks';
        $due_label = '<strong class="label label-primary">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    } elseif ($time_ago < 31536000) { // Less than 1 year ago
        $time_ago = floor($time_ago / 2592000); // Convert to months
        $time_unit = $time_ago == 1 ? 'month' : 'months';
        $due_label = '<strong class="label label-success">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    } else {// More than 1 year ago
        $time_ago = ceil($time_ago / 31536000); // Convert to years
        $time_unit = $time_ago == 1 ? 'year' : 'years';
        $due_label = '<strong class="label label-default">' . $time_ago . ' ' . $time_unit . ' ago</strong>';
    }
    return $due_label;
}


?>