<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$tujuan = '../index.php?page=detail_kontestan';
if ($id) {
    $tujuan .= '&id=' . $id;
}
header('Location: ' . $tujuan);
exit;
