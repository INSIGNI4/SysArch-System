<?php
$deletedLogFile = 'deleted_log.json';
$recentDeleted = [];

if (file_exists($deletedLogFile)) {
    $allDeleted = json_decode(file_get_contents($deletedLogFile), true) ?: [];

    $userId = $_SESSION['id'] ?? null;
    if ($userId) {
        $recentDeleted = array_filter($allDeleted, fn($item) => $item['deleted_by'] == $userId);

        usort($recentDeleted, fn($a, $b) => strtotime($b['deleted_at']) - strtotime($a['deleted_at']));

        $recentDeleted = array_slice($recentDeleted, 0, 10);
    }
}

if (!empty($recentDeleted)) {
    foreach ($recentDeleted as $item) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['id'] ?? '-') . "</td>";
        echo "<td>" . htmlspecialchars($item['deleted_at']) . "</td>";
        echo "<td>" . htmlspecialchars($item['table']) . "</td>";
        echo "<td>" . htmlspecialchars($item['name'] ?? '-') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No deleted files yet.</td></tr>";
}
?>
