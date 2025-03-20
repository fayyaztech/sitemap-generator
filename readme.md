# PHP Sitemap Generator

**Description:**

The PHP Sitemap Generator is a versatile and easy-to-use tool that empowers developers to effortlessly create sitemaps for their websites. With support for various sitemap formats, including XML, ROR, HTML, and URLLIST, this library simplifies the process of generating essential files to enhance search engine optimization (SEO) and ensure efficient indexing of web content.

**Key Features:**

- **XML Sitemaps:** Generate standard XML sitemaps to facilitate search engine discovery of your website's URLs.
- **ROR Sitemaps:** Create ROR (Resources of a Resource) XML files, providing additional metadata about your content.
- **HTML Sitemaps:** Generate HTML sitemaps for human-readable navigation, enhancing user experience and site accessibility.
- **URL List:** Produce URLLIST files, a plain-text list of URLs, suitable for various applications and indexing purposes.

**Requirements:**

- PHP 7.4 or higher
- Composer for dependency management
- Write permissions for the output directory

**Usage:**

**Installation:**

```bash
composer require fayyaztech/sitemap-generator
```

**Constructor Parameters:**

The `SitemapGenerator` class constructor accepts four optional parameters:

1. **`$changeFrequency`** *(string)*: Specifies how frequently the content at the URLs is likely to change. Default is `ChangeFrequency::DAILY`. Available options are:
   - `ChangeFrequency::DAILY`
   - `ChangeFrequency::WEEKLY`
   - `ChangeFrequency::MONTHLY`
   - `ChangeFrequency::YEARLY`

2. **`$domain`** *(string)*: The domain for which the sitemap is being generated. If not provided, it defaults to the current domain (or `localhost` if unavailable).

**Example Usage:**

```php
use Fayyaztech\SitemapGenerator\SitemapGenerator;

// Instantiate the generator with default parameters
$sitemapGenerator = new SitemapGenerator();

// Instantiate the generator with custom parameters
$sitemapGenerator = new SitemapGenerator(
    ChangeFrequency::WEEKLY,
    'https://example.com'
);

// Generate and save XML sitemap
$sitemapGenerator->saveToSitemap('/path/to/output'); // Saves an XML sitemap to the specified directory

// Generate and save ROR sitemap
$sitemapGenerator->saveToRor('/path/to/output'); // Saves a ROR sitemap (RSS format) to the specified directory

// Generate and save HTML sitemap
$sitemapGenerator->saveToHtmlSitemap('/path/to/output'); // Saves a human-readable HTML sitemap to the specified directory

// Generate and save URL list
$sitemapGenerator->saveToUrlList('/path/to/output'); // Saves a plain-text list of URLs to the specified directory
```

**Method Details:**

1. **`saveToSitemap(string $filePath = '.')`**  
   Generates an XML sitemap and saves it to the specified directory. The sitemap includes:
   - URLs collected from the domain.
   - Last modified date (default: current date).
   - Change frequency (e.g., daily, weekly).
   - Priority (default: 0.8).

2. **`saveToRor(string $filePath = '.')`**  
   Generates a ROR (RSS-based) sitemap and saves it to the specified directory. The ROR sitemap includes:
   - URLs collected from the domain.
   - Metadata such as title and description extracted from the URLs.

3. **`saveToHtmlSitemap(string $filePath = '.')`**  
   Generates a human-readable HTML sitemap and saves it to the specified directory. The HTML sitemap includes:
   - A list of URLs with clickable links.
   - The total number of pages.
   - The last updated date.

4. **`saveToUrlList(string $filePath = '.')`**  
   Generates a plain-text file containing a list of URLs and saves it to the specified directory. Each URL is listed on a new line.

**License:**

This project is released under the MIT License, offering flexibility and ease of use for developers while requiring proper attribution.

**Contributing:**

Contributions and feedback are welcome! To contribute:

1. Fork the repository on GitHub.
2. Create a new branch for your feature or bugfix.
3. Make your changes and ensure the code adheres to the project's coding standards.
4. Write tests for your changes, if applicable.
5. Submit a pull request with a clear description of your changes.

For major changes, please open an issue first to discuss your ideas.

**Links:**

- [GitHub Repository](https://github.com/fayyaztech/sitemap-generator)
- [Packagist Package](https://packagist.org/packages/fayyaztech/sitemap-generator)

Begin enhancing your website's discoverability and SEO with the PHP Sitemap Generator today!

**Author:**

- **Fayyaztech**
- **Email: fayyaztech@gmail.com**