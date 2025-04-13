<?php

// Include the SitemapGenerator class
require_once __DIR__ . '/../vendor/autoload.php';

// Use the SitemapGenerator class
use Fayyaztech\SitemapGenerator\ChangeFrequency;
use Fayyaztech\SitemapGenerator\SitemapGenerator;

// Instantiate the SitemapGenerator class with a custom domain (optional)
$sitemapGenerator = new SitemapGenerator(ChangeFrequency::MONTHLY, 'https://www.drbajrcollege.org');

// Alternatively, without providing a domain (it will use the current domain)
// $sitemapGenerator = new SitemapGenerator();

// Save the generated URLs to sitemap.xml
$sitemapGenerator->saveToSitemap();
$sitemapGenerator->saveToRor();
$sitemapGenerator->saveToHtmlSitemap();
$sitemapGenerator->saveToUrlList();

echo "Sitemap.xml has been generated successfully.\n";
