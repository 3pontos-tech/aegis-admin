# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.28.2@sha256:28a161f4a39c70671d0fb771a6e7a3abe6d2144c0e2a595ff72767ab63cd14f6

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
