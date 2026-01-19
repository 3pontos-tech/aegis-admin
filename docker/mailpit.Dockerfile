# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.28.3@sha256:27cfb8893806ed676042a00f4db70325a0d57cc5217349d210b6dd903e1a7e67

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
