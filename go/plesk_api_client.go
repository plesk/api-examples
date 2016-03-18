// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.
package main

import (
	"net/http"
	"crypto/tls"
	"fmt"
	"strings"
	"io/ioutil"
)

type PleskApiClient struct {
	host string
	port uint
	protocol string
	login string
	password string
	secretKey string
	InsecureSkipVerify bool
}

func NewPleskApiClient(host string) *PleskApiClient {
	c := new(PleskApiClient)
	c.host = host
	c.port = 8443
	c.protocol = "https"
	return c
}

func (c *PleskApiClient) SetPort(port uint) {
	c.port = port
}

func (c *PleskApiClient) SetProtocol(protocol string) {
	c.protocol = protocol
}

func (c *PleskApiClient) SetCredentials(login, password string) {
	c.login, c.password = login, password
}

func (c *PleskApiClient) SetSecretKey(secret_key string) {
	c.secretKey = secret_key
}

func (c *PleskApiClient) Request(request string) (response string, err error) {
	url := fmt.Sprintf("%s://%s:%d/enterprise/control/agent.php", c.protocol, c.host, c.port)

	req, err := http.NewRequest("POST", url, strings.NewReader(request))
	if err != nil { return }
	req.Header.Add("Content-type", "text/xml")
	req.Header.Add("HTTP_PRETTY_PRINT", "TRUE")
	if c.secretKey != "" {
		req.Header.Add("KEY", c.secretKey)
	} else {
		req.Header.Add("HTTP_AUTH_LOGIN", c.login)
		req.Header.Add("HTTP_AUTH_PASSWD", c.password)
	}

	client := http.Client{}
	if c.InsecureSkipVerify {
		tr := &http.Transport {
			TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
		}
		client.Transport = tr
	}

	resp, err := client.Do(req)
	if err != nil { return }
	defer resp.Body.Close()
	bytes, err := ioutil.ReadAll(resp.Body)
	response = string(bytes)
	return
}

