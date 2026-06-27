#!/usr/bin/env python3

import subprocess
import os

def run(cmd, cwd=None):
    print(f"Running: {cmd}")
    result = subprocess.run(cmd, shell=True, cwd=cwd, capture_output=True, text=True)
    if result.stdout:
        print(result.stdout)
    if result.stderr:
        print(f"Error: {result.stderr}")
    return result.returncode

os.chdir("/c/Users/robb1/Herd/panentelur")

# Reset migrations
run("php artisan migrate:reset")

# Seed data
run("php artisan db:seed")

print("Database reset and seed completed")