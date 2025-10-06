# syntax=docker/dockerfile:1.15.0

FROM redis:7-alpine3.21@sha256:7a7c6b5c49a6896d0a58ee0c6dc0dcabe14f30f0a2f7d2d1362d276fa2d43166

ENV TZ=America/Sao_Paulo

RUN set -xeu; \
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone;\
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
