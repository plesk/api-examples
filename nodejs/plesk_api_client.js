// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.
/* jshint -W069 */
"use strict";

const http = require('http');
const https = require('https');

class Client {

    constructor(host, port, protocol) {
        this._host = host;
        this._port = port || 8443;
        this._protocol = protocol || 'https';
    }

    setCredentials(login, password) {
        this._login = login;
        this._password = password;
    }

    setSecretKey(secretKey) {
        this._secretKey = secretKey;
    }

    request(body, callback) {
        let headers = {
            'Content-type': 'text/xml',
            'HTTP_PRETTY_PRINT': 'TRUE'
        };

        if (this._secretKey) {
            headers['KEY'] = this._secretKey;
        } else {
            headers['HTTP_AUTH_LOGIN'] = this._login;
            headers['HTTP_AUTH_PASSWD'] = this._password;
        }

        let options = {
            host: this._host,
            port: this._port,
            path: '/enterprise/control/agent.php',
            method: 'POST',
            headers: headers
        };

        let handler = (response) => {
            let result = '';
            response.on('data', (chunk) => result += chunk);
            response.on('end', () => callback(result));
        };

        let client = 'https' == this._protocol ? https : http;
        let request = client.request(options, handler);
        request.write(body);
        request.end();
    }

}

exports.Client = Client;
