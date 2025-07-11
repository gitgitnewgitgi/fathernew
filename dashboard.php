<?php
$LOG_FILE = __DIR__ . '/.data/validated.log';
$rows     = file_exists($LOG_FILE) ? array_reverse(file($LOG_FILE)) : [];

$seen     = [];
$unique   = [];

// 🏳️ Country flag from code
function countryFlag($cc) {
  if (!$cc || strlen($cc) !== 2) return '🌐';
  $cc = strtoupper($cc);
  return mb_convert_encoding("&#" . (127397 + ord($cc[0])) . ";&#" . (127397 + ord($cc[1])) . ";", 'UTF-8', 'HTML-ENTITIES');
}

// 🧼 De-dupe logs
foreach ($rows as $line) {
  $j = json_decode($line, true);
  if (!$j || !isset($j['email'], $j['ip'])) continue;
  $key = $j['email'] . '|' . $j['ip'];
  if (isset($seen[$key])) continue;
  $seen[$key] = true;
  $unique[] = $j;
}

usort($unique, fn($a, $b) => strcmp($b['time'] ?? '', $a['time'] ?? ''));
$total = count($unique);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>📊 Email Validation Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    :root {
      --bg: #f5f7fb;
      --fg: #333;
      --accent: #0080e2;
      --highlight: #dceeff;
    }
    body {
      font-family: Inter, sans-serif;
      background: var(--bg);
      color: var(--fg);
      padding: 30px;
    }
    h1 {
      margin-bottom: 10px;
    }
    .summary {
      font-size: 1rem;
      margin-bottom: 15px;
    }
    #filter {
      padding: 8px 12px;
      font-size: 1rem;
      width: 280px;
      max-width: 100%;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }
    th, td {
      padding: 10px 12px;
      border: 1px solid #e0e0e0;
      text-align: left;
      font-size: 0.93rem;
    }
    th {
      background: var(--highlight);
    }
    tr:nth-child(even) {
      background: #fdfdfd;
    }
    .ua {
      max-width: 260px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .flag {
      font-size: 1.1rem;
      margin-right: 4px;
    }
    @media (max-width: 768px) {
      .ua, td:nth-child(6) { display: none; }
    }
  </style>
</head>
<body>
  <h1>📊 Email Access Dashboard</h1>
  <div class="summary">
    ✅ Total Verified Emails: <strong><?= $total ?></strong><br>
    🕒 Last update: <?= date('Y-m-d H:i:s') ?> UTC
  </div>

  <input type="text" id="filter" placeholder="Search email, IP, country..." />

  <table id="logTable">
    <thead>
      <tr>
        <th>Time</th>
        <th>Email</th>
        <th>IP</th>
        <th>Country</th>
        <th>Fingerprint</th>
        <th>User Agent</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($unique as $j): ?>
        <tr>
          <td><?= htmlspecialchars($j['time'] ?? '-') ?></td>
          <td><?= htmlspecialchars($j['email']) ?></td>
          <td><?= htmlspecialchars($j['ip']) ?></td>
          <td>
            <span class="flag"><?= countryFlag($j['cc'] ?? '') ?></span>
            <?= htmlspecialchars($j['cc'] ?? '-') ?>
          </td>
          <td><?= htmlspecialchars(substr($j['fp'] ?? '-', 0, 12)) ?></td>
          <td class="ua"><?= htmlspecialchars(substr($j['ua'] ?? '-', 0, 80)) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <script>
    const input = document.getElementById('filter');
    input.addEventListener('input', function () {
      const val = input.value.toLowerCase();
      document.querySelectorAll('#logTable tbody tr').forEach(row => {
        const match = row.innerText.toLowerCase().includes(val);
        row.style.display = match ? '' : 'none';
      });
    });
  </script>
</body>
</html>
