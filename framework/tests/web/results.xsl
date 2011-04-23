<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" indent="yes" omit-xml-declaration="yes" />

    <xsl:template match="testsuites">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <style type="text/css">
                @import url("results.css");
            </style>
        </head>
        <body>

            <xsl:apply-templates select="testsuite" />

            <xsl:if test="count(//failure) &gt; 0">
            <dl class="failure details">
                <xsl:for-each select="//failure">
			        <dt>Failure</dt>
			        <dd><xsl:value-of select="." /></dd>
                </xsl:for-each>
            </dl>
            </xsl:if>

            <xsl:if test="count(//error) &gt; 0">
            <dl class="error details">
                <xsl:for-each select="//error">
			        <dt>Error</dt>
			        <dd><xsl:value-of select="." /></dd>
                </xsl:for-each>
            </dl>
            </xsl:if>

            <xsl:if test="count(//testsuite[@tests=0]) &gt; 0">
            <dl class="warning details">
                <xsl:for-each select="//testsuite[@tests=0]">
			        <dt>Warning</dt>
			        <dd>Empty test <xsl:value-of select="@name" /></dd>
			        <dd><xsl:value-of select="@file" /></dd>
		        </xsl:for-each>
            </dl>
            </xsl:if>

            <form method="get" action="">
                <input type="hidden" name="testdoxHTMLFile" value="generated/agiledocumentation.html" />
                <input type="submit" value="Generate agile documentation" />
            </form>

            <form method="get" action="">
                <input type="hidden" name="reportDirectory" value="generated/codecoverage" />
                <input type="submit" value="Generate code coverage report" />
            </form>

        </body>
        </html>
    </xsl:template>

    <xsl:template match="testsuite">
        <xsl:variable name="class">
            <xsl:choose>
                <xsl:when test="@failures &gt; 0">failures</xsl:when>
                <xsl:when test="@errors &gt; 0">errors</xsl:when>
                <xsl:otherwise>valid</xsl:otherwise>
            </xsl:choose>
        </xsl:variable>
        <div class="testsuite {$class}">
            <h1><xsl:value-of select="@name" /> test suite</h1>
            <div class="summary">
                <strong><xsl:value-of select="@tests" /></strong> test cases ran with
                <strong><xsl:value-of select="@failures" /></strong> failures and
                <strong><xsl:value-of select="@errors" /></strong> errors
            </div>
            <!--
            <xsl:if test="testcase">

            </xsl:if>

            <xsl:apply-templates select="testsuite" />
            -->
        </div>
    </xsl:template>
</xsl:stylesheet>