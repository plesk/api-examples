#!/usr/bin/env php
<?php
// Copyright 1999-2014. Parallels IP Holdings GmbH. All Rights Reserved.

/**
 * @param string $content
 * @return string
 */
function getPrettyXml($content)
{
    $xslTemplate = <<< EOF
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output encoding="UTF-8" indent="yes"/>
<xsl:strip-space elements="*"/>
<xsl:template match="*">
   <xsl:copy-of select="."/>
</xsl:template>
</xsl:stylesheet>
EOF;

    $xsl = new DOMDocument();
    $xsl->loadXML($xslTemplate);

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);

    $xml = new DOMDocument();
    if (!@$xml->loadXML($content)) {
        return $content;
    }

    return $proc->transformToXML($xml);
}

/**
 * @param string $content
 * @return string
 */
function prepareXmlContent($content)
{
    $content = getPrettyXml(filterContent($content));
    $content = preg_replace('/^<\?xml.*\n/', '', $content);
    return $content;
}

function filterContent($content)
{
    $contentXml = simplexml_load_string($content);

    if ($foundNode = $contentXml->xpath('//result/key/content')) {
        $foundNode[0][0] = 'PD94bWwgdmVyc...encoded license content here...';
    }

    return $contentXml->saveXML();
}

/**
 * @param SimpleXmlElement $node
 * @return string
 */
function getNodeTitle($node)
{
    if ($node->children()) {
        $nodeName = $node->children()[0]->getName();
        $subNodeName = getNodeTitle($node->children()[0]);
        return $nodeName . ($subNodeName ? (' > ' . $subNodeName) :  '');
    }

    return '';
}

/**
 * @param string $request
 * @return string
 */
function getRequestTitle($request)
{
    $requestXml = simplexml_load_string($request);
    return getNodeTitle($requestXml);
}

if ($argc < 2) {
    echo "Usage: generate_docs.php <file>\n";
    exit(1);
}

$indexHtmlFile = $argv[1];

$log = json_decode(file_get_contents('execution.log'), true);
$examples = [];

foreach ($log as $record) {
    $title = getRequestTitle($record['request']);
    $name = str_replace(' > ', '-', $title);

    if (array_key_exists($name, $examples)) {
        continue;
    }

    $examples[$name] = [
        'title' => $title,
        'request' => $record['request'],
        'response' => $record['response'],
    ];
}

$title = 'Plesk XML-RPC Examples';

ob_start();
require_once('template.php');
$content = ob_get_contents();
ob_end_clean();

file_put_contents($indexHtmlFile, $content);
