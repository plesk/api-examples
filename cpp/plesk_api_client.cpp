// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

#include <iostream>
#include <curl/curl.h>
#include "plesk_api_client.h"

PleskApiClient::PleskApiClient(const string& host)
{
    m_host = host;
}

void PleskApiClient::setCredentials(const string& login, const string& password)
{
    m_login = login;
    m_password = password;
}

size_t writeToString(void *ptr, size_t size, size_t count, void *stream)
{
    ((string*)stream)->append((char*)ptr, 0, size*count);
    return size*count;
}

void PleskApiClient::request(const string& request, string& response)
{
    CURL *curl;
    CURLcode res;
    struct curl_slist *headers = NULL;

    curl = curl_easy_init();

    if (curl) {
        headers = curl_slist_append(headers, "Content-type: text/xml");
        headers = curl_slist_append(headers, "HTTP_PRETTY_PRINT: TRUE");
        headers = curl_slist_append(headers, ("HTTP_AUTH_LOGIN: " + m_login).c_str());
        headers = curl_slist_append(headers, ("HTTP_AUTH_PASSWD: " + m_password).c_str());

        curl_easy_setopt(curl, CURLOPT_URL, string(string("https://") + m_host + string(":8443/enterprise/control/agent.php")).c_str());
        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0L);
        curl_easy_setopt(curl, CURLOPT_SSL_VERIFYHOST, 0L);
        curl_easy_setopt(curl, CURLOPT_POSTFIELDS, request.c_str());
        curl_easy_setopt(curl, CURLOPT_HTTPHEADER, headers);
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, writeToString);
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, &response);

        res = curl_easy_perform(curl);
        if (res != CURLE_OK) {
            fprintf(stderr, "curl_easy_perform() failed: %s\n", curl_easy_strerror(res));
        }
        curl_easy_cleanup(curl);
        curl_slist_free_all(headers);
    }
}
