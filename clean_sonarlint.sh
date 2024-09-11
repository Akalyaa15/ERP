#!/bin/bash

# Define the directory containing the SonarLint known findings store files
SONARLINT_DIR=~/.sonarlint

# Remove all known findings store files
echo "Cleaning SonarLint known findings store files..."
rm -rf $SONARLINT_DIR/known-findings-store*

echo "Cleanup complete!"

