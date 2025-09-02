# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.7@sha256:cae83a33cd9b9598e4acb210be673dda7e741d5271ed4045310ab456950a136a

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
