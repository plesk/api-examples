#include <iostream>
#include "plesk_api_client.h"

using namespace std;

int main(void)
{
    string host = getenv("REMOTE_HOST");
    string login = getenv("REMOTE_LOGIN");
    string password = getenv("REMOTE_PASSWORD");

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
