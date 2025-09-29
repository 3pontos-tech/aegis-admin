# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.9@sha256:b004cc0672d0692f097ba8ab9e5956ae08eeafee3d0a6bf2ab239750101ac538

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
