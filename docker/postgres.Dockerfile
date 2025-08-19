# syntax=docker/dockerfile:1.15.0

FROM postgres:17-alpine3.22@sha256:3406990b6e4c7192317b6fdc5680498744f6142f01f0287f4ee0420d8c74063c

ENV TZ=America/Sao_Paulo

RUN set -eux;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    apk add --no-cache postgresql-contrib postgis;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone;\
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
