<?php

// Include the SitemapGenerator class
require_once __DIR__ . '/../src/SitemapGenerator.php';
require_once __DIR__ . '/../src/ChangeFrequency.php';

// Use the SitemapGenerator class
use Fayyaztech\SitemapGenerator\ChangeFrequency;
use Fayyaztech\SitemapGenerator\SitemapGenerator;

// Instantiate the SitemapGenerator class with a custom domain (optional)
// $sitemapGenerator = new SitemapGenerator(ChangeFrequency::MONTHLY, 'http://localhost:8080'); // Old line commented
// $sitemapGenerator = new SitemapGenerator(); // Old line commented

// Instantiate SitemapGenerator with https://syp.ac.in/ and ChangeFrequency::DAILY
$sitemapGenerator = new SitemapGenerator(ChangeFrequency::DAILY, 'https://syp.ac.in/');

// Save the generated URLs to sitemap.xml
$sitemapGenerator->saveToSitemap();
$sitemapGenerator->saveToRor();
$sitemapGenerator->saveToHtmlSitemap();
$sitemapGenerator->saveToUrlList();

echo "Sitemap.xml has been generated successfully.\n";
