#!/bin/sh

if [ -f .env ]
then
  export $(cat .env | xargs)
fi

mkdir -p docker/certs
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/certs/server.key \
  -out docker/certs/server.crt \
  -subj "/CN=${SERVER_NAME-localhost}"
