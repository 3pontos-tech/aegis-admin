# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.11@sha256:e22dce5b36f93c77082e204a3942fb6b283b7896e057458400a4c88344c3df68

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
