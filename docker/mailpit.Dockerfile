# syntax=docker/dockerfile:1.15.0

FROM axllent/mailpit:v1.28.0@sha256:c076638db1e15662150be4fb62b8a6e96ef6ba5bde90c838a0239225854830f7

ENV TZ=America/Sao_Paulo

RUN set -xeu;\
    apk update;\
    apk add --no-cache tzdata nano ca-certificates;\
    ln -snf /usr/share/zoneinfo/"${TZ}" /etc/localtime;\
    echo "${TZ}" > /etc/timezone; \
    update-ca-certificates;\
    rm -rf /var/cache/apk/*;
