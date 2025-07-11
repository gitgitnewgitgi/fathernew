<?php
// Get the path after /f/
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);

// Extract the main slug
$slug = $parts[1] ?? '';

// Slug-to-redirect mapping
$tokens = [
  'kalhyge' => '/validate/XVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUUXVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUU'
];

// Match and redirect
if (isset($tokens[$slug])) {
  header("Location: " . $tokens[$slug], true, 302);
  exit;
}

// Otherwise, return friendly 404
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invalid Link</title>
  <style>
    body {
      font-family: sans-serif;
      text-align: center;
      margin-top: 60px;
      color: #444;
    }
  </style>
</head>
<body>
  <h2>Invalid or Expired Link</h2>
  <p>Please check the link again or contact support.</p>
</body>
</html>
