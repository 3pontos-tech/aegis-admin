# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.28.4@sha256:0c71d88b0fb3e2c396242ca4a272c5be50d5ab5c9ba10ba3f3316777ca1d196e

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
