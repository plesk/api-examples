<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <meta name="copyright" content="Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/highlight.min.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/highlight.min.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </head>
    <body>
        <a href="https://github.com/plesk/api-examples"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"></a>
        <div class="container">
            <div class="page-header">
                <h1><?php echo $title; ?></h1>
            </div>

            <h2>Table of Contents</h2>

            <ul>
<?php foreach ($menu as $section => $items): ?>
                <li>
                    <?php echo htmlspecialchars($section) . "\n"; ?>
                    <ul>
<?php foreach ($items as $item): ?>
                        <li><a href="#<?php echo $item['name']; ?>"><?php echo htmlspecialchars($item['subTitle']); ?></a></li>
<?php endforeach; ?>
                    </ul>
                </li>
<?php endforeach; ?>
            </ul>

            <h2>Examples</h2>

            <?php foreach ($examples as $name => $example): ?>
                <a name="<?php echo $name; ?>"></a>
                <h3><?php echo htmlspecialchars($example['title']); ?></h3>
                <p>Request:</p>
                <pre><code class="xml"><?php echo htmlspecialchars(prepareXmlContent($example['request'])); ?></code></pre>
                <p>Response:</p>
                <pre><code class="xml"><?php echo htmlspecialchars(prepareXmlContent($example['response'])); ?></code></pre>
            <?php endforeach; ?>
        </div>
    </body>
</html>
