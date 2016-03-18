// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

#include <iostream>
#include "plesk_api_client.h"

using namespace std;

int main(void)
{
    string host = getenv("REMOTE_HOST");
    string password = getenv("REMOTE_PASSWORD");
    string login;

    char *envLogin = getenv("REMOTE_LOGIN");

    if (NULL == envLogin) {
        login = "admin";
    } else {
        login = string(envLogin);
    }

    PleskApiClient *client = new PleskApiClient(host);
    client->setCredentials(login, password);

    string request = "\
        <packet>\
            <server>\
                <get_protos/>\
            </server>\
        </packet>\
    ";

    string response;
    client->request(request, response);
    cout << response;

    return 0;
}
