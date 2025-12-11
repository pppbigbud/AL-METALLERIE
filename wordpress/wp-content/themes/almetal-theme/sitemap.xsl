<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Plan du site XML - AL Métallerie</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <meta name="robots" content="noindex,follow"/>
                <style type="text/css">
                    body {
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                        color: #222;
                        background: #f5f5f5;
                        margin: 0;
                        padding: 20px;
                    }
                    .container {
                        max-width: 1200px;
                        margin: 0 auto;
                        background: #fff;
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    h1 {
                        color: #F08B18;
                        margin-bottom: 10px;
                    }
                    .intro {
                        color: #666;
                        margin-bottom: 30px;
                        padding-bottom: 20px;
                        border-bottom: 1px solid #eee;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th {
                        background: #F08B18;
                        color: #fff;
                        padding: 12px 15px;
                        text-align: left;
                        font-weight: 600;
                    }
                    td {
                        padding: 10px 15px;
                        border-bottom: 1px solid #eee;
                    }
                    tr:hover td {
                        background: #fef6ed;
                    }
                    a {
                        color: #F08B18;
                        text-decoration: none;
                    }
                    a:hover {
                        text-decoration: underline;
                    }
                    .url {
                        word-break: break-all;
                    }
                    .priority-high { color: #27ae60; font-weight: bold; }
                    .priority-medium { color: #f39c12; }
                    .priority-low { color: #95a5a6; }
                    .count {
                        background: #F08B18;
                        color: #fff;
                        padding: 3px 10px;
                        border-radius: 20px;
                        font-size: 14px;
                        margin-left: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Plan du site XML <span class="count"><xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> URLs</span></h1>
                    <p class="intro">
                        Ce fichier XML est le sitemap de <strong>AL Métallerie</strong>, utilisé par les moteurs de recherche pour indexer le site.
                        <br/>Pour plus d'informations sur les sitemaps XML, visitez <a href="https://www.sitemaps.org/" target="_blank">sitemaps.org</a>.
                    </p>
                    <table>
                        <tr>
                            <th>URL</th>
                            <th>Priorité</th>
                            <th>Fréquence</th>
                            <th>Dernière modification</th>
                        </tr>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <td class="url">
                                    <a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc"/></a>
                                </td>
                                <td>
                                    <xsl:choose>
                                        <xsl:when test="sitemap:priority &gt;= 0.8">
                                            <span class="priority-high"><xsl:value-of select="sitemap:priority"/></span>
                                        </xsl:when>
                                        <xsl:when test="sitemap:priority &gt;= 0.5">
                                            <span class="priority-medium"><xsl:value-of select="sitemap:priority"/></span>
                                        </xsl:when>
                                        <xsl:otherwise>
                                            <span class="priority-low"><xsl:value-of select="sitemap:priority"/></span>
                                        </xsl:otherwise>
                                    </xsl:choose>
                                </td>
                                <td><xsl:value-of select="sitemap:changefreq"/></td>
                                <td><xsl:value-of select="sitemap:lastmod"/></td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
