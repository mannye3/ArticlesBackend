# News Backend API

This project provides a RESTful API to retrieve and filter news articles based on search queries, filtering criteria, and user preferences.

## Features
- Search articles by keywords
- Filter articles by category, source, date range, and user preferences

## Endpoints

### Retrieve Articles
**URL:** `/api/articles`

**Method:** `GET`

### Query Parameters
| Parameter Name | Description | Example Value |
|----------------|-------------|---------------|
| `search`       | Search term for the article's title, description, or content | `technology` |
| `category`     | Filter by category | `sports` |
| `source`       | Filter by source | `CNN` |
| `date_from`    | Start date for filtering articles | `2024-01-01` |
| `date_to`      | End date for filtering articles | `2024-12-31` |
| `sources`      | List of sources to filter, separated by commas | `Reuters,CNN` |
| `categories`   | List of categories to filter, separated by commas | `technology,health` |
| `authors`      | List of authors to filter, separated by commas | `Michael Peel,Kristen Rogers` |

### Sample URLs

1. **Retrieve all articles:**
   
   http://127.0.0.1:8000/api/articles
   ```

2. **Search articles with the term `technology`:**
   
   http://127.0.0.1:8000/api/articles?search=technology
   ```

3. **Filter articles by category `sports`:**
   
   http://127.0.0.1:8000/api/articles?category=sports
   ```

4. **Filter articles by source `CNN`:**
   
   http://127.0.0.1:8000/api/articles?source=CNN
   ```

5. **Filter articles within a date range:**
   
   http://127.0.0.1:8000/api/articles?date_from=2024-01-01&date_to=2024-12-31
   ```

6. **Filter articles by multiple sources:**
   
   http://127.0.0.1:8000/api/articles?sources=Reuters,CNN
   ```

7. **Filter articles by multiple categories:**
   
   http://127.0.0.1:8000/api/articles?categories=technology,health
   ```

8. **Filter articles by authors `Michael Peel` and `Kristen Rogers`:**
  
   http://127.0.0.1:8000/api/articles?authors=Michael Peel,Kristen Rogers
   ```

9. **Combine multiple filters:**
  

   http://127.0.0.1:8000/api/articles?search=tech&category=business&date_from=2024-01-01&date_to=2024-12-31&authors=Rebecca Szkutak,Tiernan Ray
   ```

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/mannye3/ArticlesBackend.git
   ```
2. Navigate to the project directory:
   ```bash
   cd ArticlesBackend
   ```
3. Install dependencies:
   ```bash
   composer install
   ```
4. Set up the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Run migrations  to  database:
   ```bash
   php artisan migrate 
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```


## Fetching Articles
 **Fetch articles from The the available endpoints:**
   ```bash
   php artisan articles:fetch
   ```



### Scheduler Configuration
To automate article fetching, set up the Laravel Scheduler:

1. Open the `app/Console/Kernel.php` file.
2. Add the following commands to the `schedule` method:
   ```php
   protected function schedule(Schedule $schedule)
   {
        $schedule->command('articles:fetch')->everyMinute();
   }
   ```



### Running the Scheduler
To run the scheduler, execute the following command:
   ```bash
    php artisan schedule:work
   ```

---



## Testing

You can use tools like Postman  to test the API endpoints.

## License
This project is licensed under the MIT License.
