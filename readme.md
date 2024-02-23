Certainly! Here's an updated README for your `fayyaztech/sitemap-generator` package:

---

# PHP Sitemap Generator

**Description:**

The PHP Sitemap Generator is a versatile and easy-to-use tool that empowers developers to effortlessly create sitemaps for their websites. With support for various sitemap formats, including XML, ROR, HTML, and URLLIST, this library simplifies the process of generating essential files to enhance search engine optimization (SEO) and ensure efficient indexing of web content.

**Key Features:**

- **XML Sitemaps:** Generate standard XML sitemaps to facilitate search engine discovery of your website's URLs.

- **ROR Sitemaps:** Create ROR (Resources of a Resource) XML files, providing additional metadata about your content.

- **HTML Sitemaps:** Generate HTML sitemaps for human-readable navigation, enhancing user experience and site accessibility.

- **URL List:** Produce URLLIST files, a plain-text list of URLs, suitable for various applications and indexing purposes.

**Usage:**

**Installation:**

```bash
composer require fayyaztech/sitemap-generator
```

**Example Usage:**

```php
use Fayyaztech\SitemapGenerator\SitemapGenerator;

// Instantiate the generator
$sitemapGenerator = new SitemapGenerator();

// Generate XML sitemap
$sitemapGenerator->generateXmlSitemap('/path/to/output');

// Generate ROR sitemap
$sitemapGenerator->generateRorSitemap('/path/to/output');

// Generate HTML sitemap
$sitemapGenerator->generateHtmlSitemap('/path/to/output');

// Generate URLLIST
$sitemapGenerator->generateUrlList('/path/to/output');
```

**License:**

This project is released under the MIT License, offering flexibility and ease of use for developers while requiring proper attribution.

**Contributing:**

Contributions and feedback are welcome! Fork the repository, make improvements, and submit pull requests.

**Links:**

- [GitHub Repository](https://github.com/fayyaztech/sitemap-generator)
- [Packagist Package](https://packagist.org/packages/fayyaztech/sitemap-generator)

Begin enhancing your website's discoverability and SEO with the PHP Sitemap Generator today!

**Author:**

- **Fayyaztech**
- **Email: fayyaztech@gmail.com**

Feel free to customize this README further, if needed.