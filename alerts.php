<?php

function generate_alert($type, $message) {
    $alert = '<div class="alert alert-' . $type . ' alert-dismissible fade show fixed-top fixed-right" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <i class="icofont icofont-close-line-circled"></i>
                 </button>
                 <strong>' . ucfirst($type) . '!</strong> ' . $message . '
              </div>';

    return $alert;
}
?>