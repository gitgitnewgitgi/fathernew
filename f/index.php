<?php
// Get the path after /f/
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);

// Make sure we're matching the second segment (e.g., /f/kalhyge/...)
$slug = $parts[1] ?? '';

// Map slugs to redirect URLs
$tokens = [
  'kalhyge' => '/validate/?XVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUUXVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUU'
];

// Redirect if the slug is valid
if (isset($tokens[$slug])) {
  header("Location: " . $tokens[$slug], true, 302);
  exit;
}

// Handle invalid slug
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
