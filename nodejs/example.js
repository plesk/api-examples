#!/usr/bin/env node
// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

var pleskApi = require('./plesk_api_client.js');

var host = process.env.REMOTE_HOST;
var login = process.env.REMOTE_LOGIN || 'admin';
var password = process.env.REMOTE_PASSWORD;

process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';

var client = new pleskApi.Client(host);
client.setCredentials(login, password);

var request = 
  '<packet>' +
    '<server>' +
      '<get_protos/>' +
    '</server>' +
  '</packet>';

client.request(request, function(response) {
  console.log(response);
});
