<?php
// Get the full slug path: /f/kalhyge/TD765379_07_2025.xls
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);

// Check for valid structure: /f/slug/optionalFile
$slug = $parts[1] ?? ''; // This will be "kalhyge"

// Define slug-to-redirect map
$tokens = [
  'kalhyge' => '/validate/?XVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUUXVE6MBCADQwqF6fpWdluXJ_FMrcpXXxPEvXVUNVNPMEVOU1cyTzRWVEFSSEFUMzU0V1JUU'
];

// If valid, redirect
if (isset($tokens[$slug])) {
  header("Location: " . $tokens[$slug], true, 302);
  exit;
}

// Show error for invalid slugs
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invalid Link</title>
</head>
<body>
  <h2>Invalid or Expired Link</h2>
  <p>The link you clicked is not valid or has expired.</p>
</body>
</html>
