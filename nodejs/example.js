#!/usr/bin/env node
// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.
"use strict";

const pleskApi = require('./plesk_api_client.js');

let host = process.env.REMOTE_HOST;
let login = process.env.REMOTE_LOGIN || 'admin';
let password = process.env.REMOTE_PASSWORD;

process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';

let client = new pleskApi.Client(host);
client.setCredentials(login, password);

let request = `
    <packet>
        <server>
            <get_protos/>
        </server>
    </packet>
`;

client.request(request, (response) =>console.log(response));
