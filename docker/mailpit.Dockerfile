# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.5@sha256:5921fa3c3f0a34eb000a89ac8279d1f9d711e486a9a8fd094f7db5a1920256ab

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
