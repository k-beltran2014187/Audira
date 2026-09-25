#!/usr/bin/env bash
# Builds dist/audira.zip — the file you upload in WordPress (Appearance → Themes → Add New → Upload Theme).
set -euo pipefail
cd "$(dirname "$0")/.."
mkdir -p dist
rm -f dist/audira.zip
zip -rq dist/audira.zip audira -x '*.DS_Store'
echo "Built dist/audira.zip ($(du -h dist/audira.zip | cut -f1))"
