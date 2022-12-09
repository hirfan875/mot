<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($brands as $brand)
        <url>
            <loc>{{URL::to('/')}}/products?brands%5B0%5D={{$brand->slug}}</loc>
            <lastmod>{{ $brand->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
</urlset>
