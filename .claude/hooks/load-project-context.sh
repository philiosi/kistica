#!/bin/bash
# SessionStart Hook - Load project context
# This script runs when a new Claude Code session starts

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$(dirname "$SCRIPT_DIR")")"
CONTEXT_FILE="$PROJECT_ROOT/.claude/cache/project-context.md"

echo "=== KISTI CA Project Context Loaded ==="
echo ""
echo "Project: KISTI CA - PKI Certificate Management System"
echo "Tech Stack: PHP 8.x, MySQL, OpenSSL"
echo ""
echo "Quick Reference:"
echo "  - CA Manager:    pki/ca/"
echo "  - RA Manager:    pki/ra/"
echo "  - Subscriber:    pki/subscriber/"
echo "  - Public Site:   pub/"
echo "  - Config:        config/config_v3.php"
echo "  - DB Functions:  include/func.mysql.php"
echo ""
echo "Use *_v3.php files (current version with MySQLi)"
echo "==========================================="
