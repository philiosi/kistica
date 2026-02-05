#!/bin/bash
# PostToolUse Hook - Related file reminders after Edit/Write
# Reads file path from stdin as JSON

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$(dirname "$SCRIPT_DIR")")"
CACHE_DIR="$PROJECT_ROOT/.claude/cache"
MISTAKE_LOG="$CACHE_DIR/mistake-candidates.jsonl"

# Read input JSON
INPUT=$(cat)
FILE_PATH=$(echo "$INPUT" | grep -oP '"file_path"\s*:\s*"\K[^"]+' 2>/dev/null || echo "")

if [ -z "$FILE_PATH" ]; then
    exit 0
fi

# Extract filename
FILENAME=$(basename "$FILE_PATH")
DIRNAME=$(dirname "$FILE_PATH")

# Track edit count for mistake detection
EDIT_COUNT_FILE="$CACHE_DIR/.edit-counts.tmp"
touch "$EDIT_COUNT_FILE"

# Count edits to this file
CURRENT_COUNT=$(grep -c "^$FILE_PATH$" "$EDIT_COUNT_FILE" 2>/dev/null || echo "0")
echo "$FILE_PATH" >> "$EDIT_COUNT_FILE"
CURRENT_COUNT=$((CURRENT_COUNT + 1))

# Warn if same file edited 3+ times
if [ "$CURRENT_COUNT" -ge 3 ]; then
    TIMESTAMP=$(date -Iseconds 2>/dev/null || date +%Y-%m-%dT%H:%M:%S)
    echo "{\"timestamp\":\"$TIMESTAMP\",\"file\":\"$FILE_PATH\",\"edit_count\":$CURRENT_COUNT}" >> "$MISTAKE_LOG"
    echo ""
    echo "[!] Warning: '$FILENAME' has been edited $CURRENT_COUNT times this session."
    echo "    Consider reviewing the changes or taking a different approach."
    echo ""
fi

# Related file reminders based on patterns
case "$FILE_PATH" in
    */config/config*.php)
        echo "[i] Config modified. Related files to check:"
        echo "    - All *_v3.php files using this config"
        echo "    - include/common_v3.php"
        ;;
    */include/func.mysql.php)
        echo "[i] DB functions modified. Related files:"
        echo "    - All files using connectDB(), DBQuery(), DBFetchRow()"
        echo "    - pki/*/common_v3.php"
        ;;
    */include/func.misc.php)
        echo "[i] Utility functions modified. Related files:"
        echo "    - All files using iError(), Redirect(), Pager_s()"
        ;;
    */pki/ca/*.php)
        echo "[i] CA module modified. Related files:"
        echo "    - pki/ca/head.php, pki/ca/tail.php (templates)"
        echo "    - pki/ca/style.css (styles)"
        echo "    - include/common_v3.php"
        ;;
    */pki/ra/*.php)
        echo "[i] RA module modified. Related files:"
        echo "    - pki/ra/head.php, pki/ra/tail.php (templates)"
        echo "    - pki/ra/style.css (styles)"
        echo "    - include/common_v3.php"
        ;;
    */pki/subscriber/*.php)
        echo "[i] Subscriber module modified. Related files:"
        echo "    - pki/subscriber/head.php, pki/subscriber/tail.php"
        echo "    - pki/subscriber/style.css"
        echo "    - include/common_v3.php"
        ;;
    */pub/*.php)
        echo "[i] Public module modified. Related files:"
        echo "    - pub/head.php, pub/tail.php (templates)"
        echo "    - pub/css/*.css (styles)"
        ;;
    */db/*.sql)
        echo "[i] Database schema modified. Related files:"
        echo "    - config/config_v3.php (DB settings)"
        echo "    - All *_v3.php files with queries"
        ;;
    *head.php|*tail.php)
        echo "[i] Template modified. All PHP files in same folder may be affected."
        ;;
    *.css)
        echo "[i] Stylesheet modified. Check related HTML output."
        ;;
esac
