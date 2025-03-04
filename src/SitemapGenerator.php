<?php

/**
 * Site Map Generator
 *
 * @license    MIT
 */

namespace Fayyaztech\SitemapGenerator;

class SitemapGenerator
{
    /**
     * domain
     *
     * @var string
     */
    private $domain;

    /**
     * changeFrequency
     *
     * @var string
     */
    private $changeFrequency;

    /**
     * urlList
     *
     * @var array
     */
    private $urlList = [];

    /**
     * @var array List of excluded file extensions
     * These extensions are typically for binary files that shouldn't be in sitemaps
     */
    private const EXCLUDED_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'mp3',
        'mp4',
        'zip'
    ];

    /**
     * Constructor for SitemapGenerator
     * @param string $changeFrequency Frequency of changes (default: DAILY)
     * @param string $domain Domain to generate sitemap for (default: current domain)
     */
    public function __construct(string $changeFrequency = ChangeFrequency::DAILY, string $domain = '')
    {
        $this->changeFrequency = $changeFrequency;
        // Use current domain if no valid domain is provided
        $this->domain = $domain && filter_var($domain, FILTER_VALIDATE_URL) ? $domain : $this->getCurrentDomain();
        $this->getAllUrls();
    }

    /**
     * Get the current domain from server variables
     * Falls back to 'localhost' if HTTP_HOST is not available
     * @return string Current domain name
     */
    private function getCurrentDomain(): string
    {
        return $_SERVER['HTTP_HOST'] ?? 'localhost';
    }

    /**
     * Crawl the website and collect all valid URLs
     * Uses DOMDocument to parse HTML and extract links
     * Handles libxml errors internally
     */
    private function getAllUrls(): void
    {
        $urlList = [];
        $baseDomain = parse_url($this->domain, PHP_URL_HOST);

        try {
            // Fetch HTML content from the domain
            $html = $this->fetchHtmlContent($this->domain);
            $dom = new \DOMDocument;

            // Suppress HTML parsing errors
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);

            // Find all anchor tags in the document
            foreach ($dom->getElementsByTagName('a') as $link) {
                $href = $link->getAttribute('href');

                // Process only non-empty URLs
                if (!empty($href)) {
                    $url = $this->processUrl($href, $baseDomain);
                    if ($url) {
                        $urlList[] = $url;
                    }
                }
            }
        } finally {
            // Clean up libxml errors
            libxml_clear_errors();
            libxml_use_internal_errors(false);
        }

        // Remove duplicate URLs
        $this->urlList = array_unique($urlList);
    }

    /**
     * Fetch HTML content from a URL using cURL
     * @param string $url URL to fetch
     * @return string HTML content or empty string on failure
     */
    private function fetchHtmlContent(string $url): string
    {
        $ch = curl_init();
        // Configure cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,  // Follow redirects
            CURLOPT_MAXREDIRS => 5,          // Maximum number of redirects
            CURLOPT_TIMEOUT => 10,           // Timeout in seconds
            CURLOPT_USERAGENT => 'SitemapGenerator/1.0'  // Set user agent
        ]);

        $html = curl_exec($ch);
        curl_close($ch);

        return $html ?: '';
    }

    /**
     * Process and validate a URL
     * @param string $href URL to process
     * @param string $baseDomain Base domain for validation
     * @return string|null Valid URL or null if invalid
     */
    private function processUrl(string $href, string $baseDomain): ?string
    {
        // Handle relative URLs
        if (strpos($href, '/') === 0) {
            $url = $this->domain . $href;
        }
        // Handle absolute URLs
        elseif (filter_var($href, FILTER_VALIDATE_URL)) {
            $url = $href;
        } else {
            return null;
        }

        // Ensure URL belongs to the same domain
        if (parse_url($url, PHP_URL_HOST) !== $baseDomain) {
            return null;
        }

        // Check if URL has an excluded extension
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        if (in_array($extension, self::EXCLUDED_EXTENSIONS)) {
            return null;
        }

        return $url;
    }

    /**
     * Save the sitemap as an XML file
     * @param string $filePath Directory to save the file
     */
    public function saveToSitemap(string $filePath = '.'): void
    {
        $filePath = rtrim($filePath, '/');
        // Create XML structure with proper namespaces
        $xml = new \SimpleXMLElement(
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"></urlset>'
        );

        // Add each URL to the sitemap
        foreach ($this->urlList as $url) {
            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', htmlspecialchars($url));
            $urlElement->addChild('lastmod', date('c'));  // Current date in ISO 8601 format
            $urlElement->addChild('changefreq', $this->changeFrequency);
            $urlElement->addChild('priority', '0.80');  // Default priority
        }

        // Save the XML file
        $xml->asXML($filePath . '/sitemap.xml');
    }

    /**
     * Method saveToRor
     *
     * @param $filePath $filePath [location of save file]
     *
     * @return void
     */
    public function saveToRor($filePath = '.')
    {
        $xml = new \SimpleXMLElement('<rss xmlns:ror="http://rorweb.com/0.1/" version="2.0"></rss>');
        $channel = $xml->addChild('channel');

        $channel->addChild('title', 'ROR Sitemap for ' . htmlspecialchars($this->domain));
        $channel->addChild('link', $this->domain);
        $channel->addChild('description', "ROR sitemap For " . $this->domain);


        foreach ($this->urlList as $url) {
            $item = $channel->addChild('item');
            $item->addChild('link', htmlspecialchars($url));

            // Extract title and description from the meta tags of each URL
            $metaTags = $this->getMetaTags($url);
            $item->addChild('title', htmlspecialchars($metaTags['title']));
            $item->addChild('description', htmlspecialchars($metaTags['description']));
            $item->addChild('ror:updatePeriod', $this->changeFrequency);
            $item->addChild('ror:sortOrder', '0');
            $item->addChild('ror:resourceOf', 'sitemap');
        }

        $xml->asXML($filePath . '/ror.xml');
    }

    /**
     * Method getMetaTags
     *
     * @param $url $url [url of perticular page]
     *
     * @return array
     */
    private function getMetaTags($url)
    {
        $metaTags = ['title' => '', 'description' => ''];

        $html = file_get_contents($url);

        if ($html !== false) {
            $doc = new \DOMDocument;
            libxml_use_internal_errors(true);
            $doc->loadHTML($html);
            libxml_clear_errors();

            $titleNode = $doc->getElementsByTagName('title')->item(0);
            if ($titleNode) {
                $metaTags['title'] = trim($titleNode->textContent);
            }

            $metaNodes = $doc->getElementsByTagName('meta');
            foreach ($metaNodes as $metaNode) {
                $name = $metaNode->getAttribute('name');
                $content = $metaNode->getAttribute('content');
                if ($name == 'description') {
                    $metaTags['description'] = $content;
                    break;
                }
            }
        }

        return $metaTags;
    }

    // save to html 
    public function saveToHtmlSitemap($filePath = '.')
    {
        $html = '<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>' . htmlspecialchars($this->domain) . ' Site Map - Generated by www.xml-sitemaps.com</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <style type="text/css">
        /* ... (your existing CSS styles) ... */
    </style>
</head>

<body>
    <div id="top">
        <nav>' . htmlspecialchars($this->domain) . ' HTML Site Map</nav>
        <h3>
            <span>Last updated: ' . date('Y, F d') . '<br />
                Total pages: ' . count($this->urlList) . '</span>
            <a href="' . htmlspecialchars($this->domain) . '">' . htmlspecialchars($this->domain) . ' Homepage</a>
        </h3>
    </div>
    <div id="cont">
        <ul class="level-0">';

        foreach ($this->urlList as $url) {
            $html .= '
            <li class="lpage"><a href="' . htmlspecialchars($url) . '" title="' . htmlspecialchars($url) . '">' . htmlspecialchars($url) . '</a></li>';
        }

        $html .= '
        </ul>
    </div>
    <div id="footer">
        Page created with <a target="_blank" href="https://www.sublimetechnologies.in">html sitemaps generator</a> | Copyright &copy; 2018-2024 www.sublimetechnologies.in
    </div>
</body>

</html>';

        file_put_contents($filePath . '/sitemap.html', $html);
    }


    // url list 
    function saveToUrlList($filePath = '.')
    {
        $content = implode(PHP_EOL, $this->urlList);
        file_put_contents($filePath . '/urllist.txt', $content);
    }
}
