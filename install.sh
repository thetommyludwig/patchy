#!/bin/sh
# Install script to create IMHpatchman plugin directory and get the files
#

clear
echo "Installing IMH patchman cpanel plugin"

# Create the directory for the plugin
mkdir -p /usr/local/cpanel/base/frontend/paper_lantern/IMHpatchman

# Get the plugin files from Github
#curl -s https://github.com/thetommyludwig/patchy.git.gz > /root/patchy.tar.gz

# Uncompress the archive
#tar xzf /root/patchy.tar.gz

# Move files to /usr/local/cpanel/base/frontend/paper_lantern/cppatchman directory
mv patch.livephp /usr/local/cpanel/base/frontend/paper_lantern/IMHpatchman

# Install the plugin (which also places the png image in the proper location)
#/usr/local/cpanel/scripts/install_plugin /root/patchy/patchyplugin.tar.gz

echo "Installation is complete!"
