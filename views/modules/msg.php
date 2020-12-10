<div id="msg" class="row">
    <?php
    if (isset($msg)) {
        if ($msg['msg']['info'] !== '') {
            echo '<p class="col-12 msg-info">' . $msg['msg']['info'] . '</p>';
        }

        if ($msg['msg']['error'] !== '') {
            echo '<p class="col-12 msg-error">' . $msg['msg']['error'] . '</p>';
        }

        if ($msg['msg']['conf'] !== '') {
            echo '<p class="col-12 msg-conf">' . $msg['msg']['conf'] . '</p>';
        }
    }
    ?>
</div>
