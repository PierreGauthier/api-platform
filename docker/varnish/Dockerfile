# Varnish image
FROM varnish:stable

RUN apt-get update && apt-get install --no-install-recommends -y gettext

COPY --link --chmod=755 ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
