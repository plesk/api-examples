// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

#ifndef PLESK_API_CLIENT_H
#define PLESK_API_CLIENT_H

#include <iostream>

using namespace std;

class PleskApiClient
{

private:
    string m_host;
    string m_login;
    string m_password;

    PleskApiClient() {}

public:
    PleskApiClient(const string& host);
    void setCredentials(const string& login, const string& password);
    void request(const string& request, string& response);

};

#endif
