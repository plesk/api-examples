#!/usr/bin/env php
<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

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
    $nodeTitle = getNodeTitle($requestXml);

    $parts = explode(' > ', $nodeTitle);
    $partsLimit = 2;

    if (preg_match('/^server > /', $nodeTitle)) {
        $partsLimit = 3;
    }

    $nodeTitle = join(' > ', array_slice($parts, 0, $partsLimit));

    return $nodeTitle;
}

if ($argc < 2) {
    echo "Usage: generate_docs.php <file>\n";
    exit(1);
}

$indexHtmlFile = $argv[1];

$log = json_decode(file_get_contents('execution.log'), true);
$examples = [];
$menu = [];
$contentHashes = [];

foreach ($log as $record) {
    $trace = join(';', array_column($record['trace'], 'file'));
    if (preg_match('/ApiClientTest\.php/', $trace)) {
        continue;
    }

    $origTitle = $title = getRequestTitle($record['request']);
    $origName = $name = str_replace(' > ', '-', $title);

    $index = 2;
    while (array_key_exists($name, $examples)) {
        $title = $origTitle . " ($index)";
        $name = $origName . '-' . $index++;
    }

    $contentHash = md5(preg_replace('/>([^<]+?)</', '><', $record['request']));
    if (array_key_exists($contentHash, $contentHashes)) {
        continue;
    } else {
        $contentHashes[$contentHash] = true;
    }

    $examples[$name] = [
        'title' => $title,
        'request' => $record['request'],
        'response' => $record['response'],
    ];
}

ksort($examples);

foreach ($examples as $name => $example) {
    $titleParts = explode(' > ', $example['title']);
    $operator = $titleParts[0];
    $menuItem = [
        'subTitle' => join(' > ', array_slice($titleParts, 1)),
        'name' => $name,
    ];

    if (!isset($menu[$operator])) {
        $menu[$operator] = [$menuItem];
    } else {
        $menu[$operator][] = $menuItem;
    }
}

$title = 'Plesk XML-RPC Examples';

ob_start();
require_once('template.php');
$content = ob_get_contents();
ob_end_clean();

file_put_contents($indexHtmlFile, $content);
