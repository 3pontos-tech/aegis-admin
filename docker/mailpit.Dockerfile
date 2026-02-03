# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.29.0@sha256:0f93f36075f5f6fe428655d94299dda59b9a533d3fe9ce62909a72ac6fe455a9

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
