#!/bin/sh
set -e  # Stop on error

whoami
su -s /bin/bash www-data -c "install.sh"
whoami

echo "Starting app..."
exec "$@"
