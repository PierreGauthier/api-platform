#!/bin/sh
set -e

envsubst < /etc/varnish/default.vcl.template > /etc/varnish/default.vcl

exec docker-varnish-entrypoint "$@"
