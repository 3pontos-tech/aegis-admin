# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.27.100@sha256:b1f1be18af530d939a11ee8820b379e0c88eeec204d904bfad68862adced3a5a

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
