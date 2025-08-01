# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.3@sha256:7b687e9fbc26252866580819733f2dce47edde9b6bf4444da3321fdd06932f02

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
