# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.6@sha256:68c257dc344553a364a71c758acd18708f21073a78d0597e0120c3c92f03561f

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
