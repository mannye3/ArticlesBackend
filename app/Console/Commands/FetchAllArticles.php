<?php

namespace App\Console\Commands;

use App\Models\Article; // Replace with your unified model
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Carbon\Carbon;

class FetchAllArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from The Guardian, NewsAPI, and NYTimes APIs';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->fetchGuardianArticles();
        $this->fetchNewsApiArticles();
        $this->fetchNYTimesArticles();
        $this->info('All articles fetched and stored successfully.');
    }

    /**
     * Fetch articles from The Guardian API.
     */
    private function fetchGuardianArticles()
    {
        $apiKey = 'bce8ba5b-ae3e-4e1b-8307-c1b8000c2434';
        $url = "https://content.guardianapis.com/search?api-key={$apiKey}";

        $response = Http::get($url);

        if ($response->successful()) {
            $articles = $response->json()['response']['results'];

            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['url' => $article['webUrl']],
                    [
                        'title' => $article['webTitle'] ?? 'No Title',
                        'author' => null,
                        'source' => 'The Guardian',
                        'category' => $article['sectionName'] ?? 'Uncategorized',
                        'description' => $article['fields']['trailText'] ?? null,
                        'content' => $article['fields']['trailText'] ?? null,
                        'image_url' => $article['fields']['thumbnail'] ?? null,
                        'published_at' => isset($article['webPublicationDate'])
                            ? Carbon::parse($article['webPublicationDate'])->format('Y-m-d')
                            : null,
                    ]
                );
            }

            $this->info('The Guardian articles fetched successfully.');
        } else {
            $this->error('Failed to fetch articles from The Guardian.');
        }
    }

    /**
     * Fetch articles from NewsAPI.
     */
    private function fetchNewsApiArticles()
    {
        $apiKey = '6cf929b3c09540cbb601c5701e551a5c';
        $categories = ['business', 'technology', 'sports', 'health', 'entertainment'];

        foreach ($categories as $category) {
            $url = "https://newsapi.org/v2/top-headlines?category={$category}&apiKey={$apiKey}";
            $response = Http::get($url);

            if ($response->successful()) {
                $articles = $response->json()['articles'];

                foreach ($articles as $article) {
                    Article::updateOrCreate(
                        ['url' => $article['url']],
                        [
                            'title' => $article['title'],
                            'author' => $article['author'] ?? 'Unknown',
                            'source' => $article['source']['name'] ?? null,
                            'category' => $category,
                            'description' => $article['description'] ?? null,
                            'content' => $article['content'] ?? null,
                            'image_url' => $article['urlToImage'] ?? null,
                            'published_at' => isset($article['publishedAt'])
                                ? Carbon::parse($article['publishedAt'])->format('Y-m-d')
                                : null,
                        ]
                    );
                }

                $this->info("NewsAPI articles for category '{$category}' fetched successfully.");
            } else {
                $this->error("Failed to fetch articles for category '{$category}' from NewsAPI.");
            }
        }
    }

    /**
     * Fetch articles from NYTimes API.
     */
    private function fetchNYTimesArticles()
    {
        $apiKey = 'yXrnLYH3t7eZVLFasSl8k7HzOAJ9e7En';
        $client = new Client();
        $endpoint = 'https://api.nytimes.com/svc/news/v3/content/all/all.json';

        try {
            $response = $client->get($endpoint, [
                'query' => ['api-key' => $apiKey]
            ]);

            $articles = json_decode($response->getBody()->getContents(), true);

            foreach ($articles['results'] as $article) {
                Article::updateOrCreate(
                    ['url' => $article['url']],
                    [
                        'title' => $article['title'],
                        'description' => $article['abstract'] ?? '',
                        'content' => $article['abstract'] ?? '',
                        'author' => $article['byline'] ?? '',
                        'source' => 'NYTimes',
                        'category' => $article['section'] ?? '',
                        'image_url' => $article['multimedia'][0]['url'] ?? null,
                        'published_at' => isset($article['published_date'])
                            ? Carbon::parse($article['published_date'])->format('Y-m-d')
                            : null,
                    ]
                );
            }

            $this->info('NYTimes articles fetched successfully.');
        } catch (\Exception $e) {
            $this->error('Error fetching NYTimes articles: ' . $e->getMessage());
        }
    }
}
