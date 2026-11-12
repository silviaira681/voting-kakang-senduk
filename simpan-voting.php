<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Location: ../index.php', true, 307);
    exit;
}

header('Location: ../index.php?page=kontestan');
exit;
