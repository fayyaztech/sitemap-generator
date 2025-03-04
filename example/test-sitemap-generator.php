<?php

// Include the SitemapGenerator class
require_once '../src/SitemapGenerator.php';
require_once '../src/ChangeFrequency.php';

// Use the SitemapGenerator class
use Fayyaztech\SitemapGenerator\ChangeFrequency;
use Fayyaztech\SitemapGenerator\SitemapGenerator;

// Instantiate the SitemapGenerator class with a custom domain (optional)
$sitemapGenerator = new SitemapGenerator(ChangeFrequency::MONTHLY, 'http://localhost:8080');

// Alternatively, without providing a domain (it will use the current domain)
// $sitemapGenerator = new SitemapGenerator();

// Save the generated URLs to sitemap.xml
$sitemapGenerator->saveToSitemap();
$sitemapGenerator->saveToRor();
$sitemapGenerator->saveToHtmlSitemap();
$sitemapGenerator->saveToUrlList();

echo "Sitemap.xml has been generated successfully.\n";
