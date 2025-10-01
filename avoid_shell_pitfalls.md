### Root cause

Your shell is choking on the single-quoted PHP snippet because it contains single quotes inside the string. In Bash, backslashes do not escape single quotes within single-quoted strings, so the outer quote closes early and Bash sees raw parentheses, triggering “syntax error near unexpected token ‘(’”. The error is from Bash, not PHP.

---

### Quick fixes

#### Use a single-quoted heredoc (cleanest)
```bash
php <<'PHP'
<?php
$html = file_get_contents('/tmp/homepage.html');
$pattern = "/pricing:\s*JSON\.parse\('((?:\\\\'|[^'])*)'\)/";
if (preg_match($pattern, $html, $matches)) {
    echo $matches[1];
} else {
    echo "no match";
}
PHP
```
- The `<<'PHP'` heredoc is literal: no shell interpolation, no need to escape single quotes. This is the most robust approach.

#### Use double quotes around the `-r` code and escape shell-sensitive characters
```bash
php -r "$html = file_get_contents('/tmp/homepage.html'); \
\$pattern = \"/pricing:\\s*JSON\\.parse\\('((?:\\\\\\\\'|[^'])*)'\\)/\"; \
if (preg_match(\$pattern, \$html, \$matches)) { echo \$matches[1]; } else { echo 'no match'; }"
```
- Escape: `\$` to protect PHP variables from shell, and double-escape backslashes in the regex (`\\\\'`) because the shell consumes one layer.

#### Escape single quotes inside a single-quoted string (painful but works)
```bash
php -r '$html = file_get_contents("/tmp/homepage.html"); $pattern = "/pricing:\s*JSON\.parse\('\''((?:\\'\''|[^'\''])*)'\''\)/"; if (preg_match($pattern, $html, $matches)) { echo $matches[1]; } else { echo "no match"; }'
```
- Technique: end quote → insert `'\''` → resume. Readability suffers; use if you must stay with single quotes.

---

### Best practice for repeatability

#### Put the PHP into a script file
Create `extract_pricing.php`:
```php
<?php
$html = file_get_contents('/tmp/homepage.html');
$pattern = "/pricing:\s*JSON\.parse\('((?:\\\\'|[^'])*)'\)/";
if (preg_match($pattern, $html, $matches)) {
    echo $matches[1];
} else {
    echo "no match";
}
```
Run:
```bash
php extract_pricing.php
```
- Eliminates shell quoting altogether, improving maintainability and CI reliability.

---

### Bonus: Decode the JSON payload safely

If the matched content is JSON text inside the `JSON.parse('...')`, decode it in PHP and print a normalized JSON.

```php
<?php
$html = file_get_contents('/tmp/homepage.html');

$pattern = "/pricing:\s*JSON\.parse\('((?:\\\\'|[^'])*)'\)/";
if (!preg_match($pattern, $html, $matches)) {
    fwrite(STDERR, "no match\n");
    exit(1);
}

$raw = $matches[1];

// Convert JavaScript-style escapes to actual characters.
// The captured string should already be valid JSON text;
// remove JS single-quote escapes (\' → ') and unescape backslashes.
$normalized = str_replace("\\'", "'", $raw);

// Try to decode as JSON
$data = json_decode($normalized, true, 512, JSON_THROW_ON_ERROR);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
```
- If your page uses `JSON.parse(decodeURIComponent('...'))`, run `urldecode` on `$raw` before `json_decode`.
- If the source uses double quotes around the JS string, adjust the regex accordingly.

---

### Sanity checks and guardrails

- **Verify the match location:** Print a small surrounding slice to ensure the regex anchors correctly.
  ```php
  // Debug: show first 200 chars around the match
  $pos = strpos($html, $matches[0]);
  if ($pos !== false) {
      echo substr($html, max(0, $pos - 100), 200) . PHP_EOL;
  }
  ```
- **Fail fast in CI:** Exit with non-zero code on “no match” so pipelines catch regressions.
- **Stabilize the regex:** If markup varies, widen to tolerate whitespace/newlines:
  ```php
  $pattern = "/pricing:\s*JSON\.parse\(\s*'((?:\\\\'|[^'])*)'\s*\)/s";
  ```
- **Avoid shell pitfalls:** Prefer heredocs or script files for complex PHP one-liners. This removes quote-escaping hazards entirely.

https://copilot.microsoft.com/shares/bDXfKtRHc5ybcXHAtJGi2
