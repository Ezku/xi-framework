<?php
/**
 * This file is used to run the test suite from a web browser
 */
$web  = dirname(__FILE__);
$root = dirname($web);

require $root . '/bootstrap.php';

$configuration = 'alltests.xml';
$forward       = null;
switch (true) {
    case isset($_REQUEST['reportDirectory']):
        $configuration = 'codecoverage.xml';
        $forward       = $_REQUEST['reportDirectory'];
        $_REQUEST['reportDirectory'] = $web . DIRECTORY_SEPARATOR . $_REQUEST['reportDirectory'];
    break;
    case isset($_REQUEST['testdoxHTMLFile']):
        $configuration = 'testdox.xml';
        $forward       = $_REQUEST['testdoxHTMLFile'];
        $_REQUEST['testdoxHTMLFile'] = $web . DIRECTORY_SEPARATOR . $_REQUEST['testdoxHTMLFile'];
    break;
}

/**
 * Omit output from result printer
 */
ob_start();
Xi_AllTests::main(array(
    'configuration' => $root . '/configuration/' . $configuration,
    'xmlLogfile' => dirname(__FILE__) . '/results.xml',
    'logIncompleteSkipped' => true,
    'reportCharset' => 'utf-8'
));
ob_end_clean();

if (isset($forward)) {
    header('Location: ' . $forward);
    die();
}

$xml = new DOMDocument;
$xml->load(dirname(__FILE__) . '/results.xml');

$xsl = new DOMDocument;
$xsl->load(dirname(__FILE__) . '/results.xsl');

$xslt = new XSLTProcessor;
$xslt->importStyleSheet($xsl);
echo $xslt->transformToXML($xml);
