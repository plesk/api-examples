<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
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
        <div class="container">
            <h1><?php echo $title; ?></h1>

            <h2>Table of Contents</h2>

            <ul>
                <?php foreach ($toc as $index => $tocItem): ?>
                    <li><a href="#example-<?php echo $index; ?>"><?php echo htmlspecialchars($tocItem); ?></a></li>
                <?php endforeach; ?>
            </ul>

            <h2>Examples</h2>

            <?php foreach ($examples as $index => $example): ?>
                <a name="example-<?php echo $index; ?>"></a>
                <h3><?php echo htmlspecialchars($toc[$index]); ?></h3>
                <p>Request:</p>
                <pre><code class="xml"><?php echo htmlspecialchars(prepareXmlContent($example['request'])); ?></code></pre>
                <p>Response:</p>
                <pre><code class="xml"><?php echo htmlspecialchars(prepareXmlContent($example['response'])); ?></code></pre>
            <?php endforeach; ?>
        </div>
    </body>
</html>
