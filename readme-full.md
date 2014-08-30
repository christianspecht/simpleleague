![logo](https://bitbucket.org/christianspecht/simpleleague/raw/tip/img/logo128x128.png)

SimpleLeague is a very *(and I mean **very**)* minimalistic tool to display tabular league data *(results, rankings etc.)* in existing websites.

---

## Links

- [Download page](https://bitbucket.org/christianspecht/simpleleague/downloads)
- [Report a bug](https://bitbucket.org/christianspecht/simpleleague/issues/new)
- [Main project page on Bitbucket](https://bitbucket.org/christianspecht/simpleleague)

---

## Why SimpleLeague?

I needed a simple way to insert database-driven HTML tables with results and rankings into an existing site, which was created with a CMS.  
There weren't any plugins available for that CMS which provided anything like this, so I had to build it myself...but I wanted to do it with the least effort possible *(because I knew that I would need to enter only a few results per year)*.

That's why I decided to build a "technology-agnostic" and **very** minimalistic solution:  
SimpleLeague just consists of a few PHP files that spit out raw HTML tables. You need a server with [PHP](http://php.net/) and [MySQL](http://www.mysql.com/) to install it, but you can integrate it in websites built with any technology, as long as you are able to include content from external URLs.  
There isn't even an admin screen, and it's unlikely that there will ever be one.  
To enter players, results etc., you just edit the database directly in [phpMyAdmin](http://www.phpmyadmin.net/) *(or whatever tool your server provides)*.  
Yes, it's **that** minimalistic.  
And yes, this means that you probably need to be a developer to be able to use SimpleLeague.  

---

## How to install

You need a web server with [PHP](http://php.net/) and a [MySQL](http://www.mysql.com/) database.

To install SimpleLeague, just:

0. Download the current ZIP file from the download page *(see "Links" section above)*
0. Copy the content of the `src` folder onto your server
0. Set the permissions of the `cache` subfolder to `777`
0. Create a new MySQL database and execute the content of [`db.sql`](https://bitbucket.org/christianspecht/simpleleague/src/tip/db.sql) to create the tables
0. Change the database connection settings in [`inc.config.php`](https://bitbucket.org/christianspecht/simpleleague/src/tip/src/inc.config.php) *(server, database, user and password)*
0. *Optional: Edit the other constants in the config file if you want to edit the terminology used in the table headers (like **Player**, **Season** and so on).*

To integrate it with your site, use whatever is provided by the technology your site was built with, for example:

- HTML inline frames
- [PHP `include`](http://php.net/manual/en/function.include.php) (with [`allow_url_include`](http://www.php.net/manual/en/filesystem.configuration.php#ini.allow-url-include) enabled)
- [Server Side Includes](http://en.wikipedia.org/wiki/Server_Side_Includes)

For example URLs, see below.

---

## Terminology and URLs

The following text gives a short explanation of SimpleLeague's core concepts and how they link to the database.  

**Bold** words refer to a table of the same name.

The "core data" of SimpleLeague are **players** and (optional) **teams**.  
Think of something like [StarCraft](http://en.wikipedia.org/wiki/StarCraft): there are two "players" *(actual humans)*, but their "teams" are Terrans, Zerg or Protoss.

There are one or more **seasons**.  
A season has a name *(like "2013")* and consists of one or more **rounds**.

Each round has a number and can optionally have a name *(something like "Winter 2013")*.  
A round consists of one or more **games**.

Each game has exactly two players facing each other.  
*(or just one player if he has no opponent - in this case, just set the opponent's `player_id` to `0`)*  
The players score *points* and *victory points*.  
Think of the way scores in sports leagues work: the actual game result is something like 13 : 8 *victory points (goals, touchdowns...whatever)*, and the winner gets 2 *points* for winning, which directly affect his position in the league rankings. So the position in the rankings is mainly determined by the amount of "league points", the victory points only matter when there's a draw in league points.  

---

You can use [this SQL script](https://bitbucket.org/christianspecht/simpleleague/src/tip/db_sample_data.sql) to create a database with sample data.

Here is a list of the available URLs, with examples how the rendered tables look like.  
The example tables use the sample data from the script above.

### List of players (and their teams) for a season:

URL:

	/season_players/?season_name=2013

Example:

<iframe frameborder="0" height="150" src="http://simpleleaguedemo.christianspecht.de/season_players/?season_name=2013" width="100%"><a href="http://simpleleaguedemo.christianspecht.de/season_players/?season_name=2013">here</a></iframe>


### Schedule of all rounds and games for a season:

URL:

	/season_schedule/?season_name=2013

Optional: To display a single round, pass the round number as the second parameter:

	/season_schedule/?season_name=2013&round_number=2

Example:  
*(without the "round number" parameter)*

<iframe frameborder="0" height="400" src="http://simpleleaguedemo.christianspecht.de/season_schedule/?season_name=2013" width="100%"><a href="http://simpleleaguedemo.christianspecht.de/season_schedule/?season_name=2013">here</a></iframe>


### Results for a single round:

*Note: only visible when the round is **finished**.  
A round is considered finished when the `finished` column in the `rounds` table is set to `1`.*

URL:

	/round_results/?season_name=2013&round_number=2

Example:

<iframe frameborder="0" height="120" src="http://simpleleaguedemo.christianspecht.de/round_results/?season_name=2013&round_number=2" width="100%"><a href="http://simpleleaguedemo.christianspecht.de/round_results/?season_name=2013&round_number=2">here</a></iframe>


### Current ranking:

*Note: only incorporates the results of finished rounds where the round number is equal or smaller than the specified one.  
A round is considered finished when the `finished` column in the `rounds` table is set to `1`.*

URL:

	/season_ranking/?season_name=2013&round_number=2

Example:

<iframe frameborder="0" height="150" src="http://simpleleaguedemo.christianspecht.de/season_ranking/?season_name=2013&round_number=2" width="100%"><a href="http://simpleleaguedemo.christianspecht.de/season_ranking/?season_name=2013&round_number=2">here</a></iframe>


---

## Development

**Export settings for phpMyAdmin to create `db.sql`:**

- structure only *(no data!)*
- `AUTO_INCREMENT` off

**How to make a release:**

Prerequisites:

- Windows
- The script expects the `7za.exe` from the [7-Zip Command Line Version](http://www.7-zip.org/download.html) in the parent folder

Steps:

1. Increase the version number in `version-number.bat`
2. Run `release.bat` (this will create a zip file in the `release` subfolder)

---

### Acknowledgements

SimpleLeague makes use of the following open source projects:

- [Mustache.php](https://github.com/bobthecow/mustache.php)

<a name="license"></a>

---

### License

SimpleLeague is licensed under the MIT License. See [License.txt](https://bitbucket.org/christianspecht/simpleleague/src/tip/license.txt) for details.

---

### Project Info

<script type="text/javascript" src="http://www.ohloh.net/p/710714/widgets/project_basic_stats.js"></script>  
<script type="text/javascript" src="http://www.ohloh.net/p/710714/widgets/project_languages.js"></script>