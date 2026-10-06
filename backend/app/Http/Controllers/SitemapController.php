<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap.
     *
     * @return Response
     */
    public function index()
    {
        $urls = [];
        
        // Homepage
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];
        
        // Products page
        $urls[] = [
            'loc' => url('/products'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];
        
        // All products
        $products = Product::where('is_available', true)->get();
        foreach ($products as $product) {
            $urls[] = [
                'loc' => url("/details/{$product->id}"),
                'lastmod' => $product->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }
        
        // Categories (if you have category pages)
        $categories = Category::all();
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => url("/category/{$category->id}"), // Adjust route if different
                'lastmod' => $category->updated_at->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }
        
        // Static pages
        $staticPages = [
            ['url' => '/aboutus', 'priority' => '0.6'],
            ['url' => '/contactus', 'priority' => '0.6'],
        ];
        
        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => url($page['url']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => $page['priority'],
            ];
        }
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
