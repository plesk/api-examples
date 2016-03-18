# Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

function plesk_api() {
    local request="$1"

    /usr/bin/curl -s -k https://$REMOTE_HOST:8443/enterprise/control/agent.php \
        -H "HTTP_AUTH_LOGIN: $REMOTE_LOGIN" -H "HTTP_AUTH_PASSWD: $REMOTE_PASSWORD" \
        -H "HTTP_PRETTY_PRINT: true" -H "Content-Type: text/xml" -d "$request"

}
