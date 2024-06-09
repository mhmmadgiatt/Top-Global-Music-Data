<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Music - Home</title>
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c3c1353c4c.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
    <!-- Connector untuk menghubungkan PHP dan SPARQL -->
    <?php
        require_once("sparqllib.php");
        $search = "" ;
        
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
            $data = sparql_get(
            "http://localhost:3030/music",
            "
                PREFIX owl: <http://www.w3.org/2002/07/owl#>
                PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
                PREFIX ex: <http://example.org/>
                PREFIX id: <https://songs.com/>
                PREFIX item: <https://songs.com/ns/item#>
                PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

                SELECT ?Album2 ?Artist2 ?Ranking2 ?Year2
                WHERE
                { 
                    ?items
                        ex:Album2 ?Album2 ;
                        ex:Artist2 ?Artist2 ;
                        ex:Ranking2 ?Ranking2 ;
                        ex:Year2 ?Year2 .
                    FILTER 
                    (regex(?Album2, '$search', 'i') 
                    || regex(?Artist2, '$search', 'i'))
                }
                ORDER BY ASC(?Ranking2)
                
            "
            );
        } else {
            $data = sparql_get(
            "http://localhost:3030/music",
            "
                PREFIX owl: <http://www.w3.org/2002/07/owl#>
                PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
                PREFIX ex: <http://example.org/>
                PREFIX id: <https://songs.com/>
                PREFIX item: <https://songs.com/ns/item#>
                PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

                SELECT ?Album2 ?Artist2 ?Ranking2 ?Year2
                WHERE
                { 
                    ?items
                        ex:Album2 ?Album2 ;
                        ex:Artist2 ?Artist2 ;
                        ex:Ranking2 ?Ranking2 ;
                        ex:Year2 ?Year2 .
                }
                ORDER BY ASC(?Year2) ASC(?Ranking2)
                LIMIT 100
            "
            );
        }

        if (!isset($data)) {
            print "<p>Error: " . sparql_errno() . ": " . sparql_error() . "</p>";
        }
    ?>

    <!-- Navbar -->
    <header class="bg-cover bg-center h-screen" style="background-image: url('https://cdn.builder.io/api/v1/image/assets/TEMP/fb6e7ec23db3b681c46d0add7ed977dd473372d538623f0b9775c8294338ea35?apiKey=5f3f9868d33e49c796f9c1903489545e');">
        <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-50">
            <nav class="flex justify-center space-x-4 text-xl text-white">
                <a href="index_a.php" class="px-4 py-2 rounded hover:bg-gray-700">Home</a>
                <a href="about.php" class="px-4 py-2 rounded hover:bg-gray-700">About</a>
            </nav>
            <h1 class="mt-10 text-7xl font-bold text-white">TOP MUSIC</h1>
            <br>
            <form class="mt-10" action="" method="post" id="search" name="search">
                <label for="searchInput" class="sr-only">Ketik Keyword disini...</label>
                <div class="flex items-center">
                    <input type="text" id="searchInput" name="search" placeholder="Ketik Keyword disini..." class="w-64 px-4 py-2 text-black bg-white border rounded-l-md focus:outline-none">
                    <button type="submit" class="px-4 py-2 text-white bg-blue-600 border border-blue-600 rounded-r-md hover:bg-blue-700">Search</button>
                </div>
            </form>
        </div>
    </header>

    <!-- Body -->
    <main class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <?php if ($search != NULL): ?>
                <div class="mb-4">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Menampilkan hasil pencarian untuk <b>"<?php echo htmlentities($search); ?>"</b></span>
                </div>
            <?php endif; ?>
               <!-- Button for Top 5000 Albums of All Time Dataset by RYM website -->
         <section class="flex justify-end my-1">
            <button 
                onclick="window.location.href='index_a.php'" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"> Best Selling Albums
            </button>
        </section>

        <h2 class="mt-10 text-7xl font-bold text-gray flex justify-center">Top 5000 Albums</h2>
            <br>
            <br>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2">Rank</th>
                            <th class="px-4 py-2">Album</th>
                            <th class="px-4 py-2">Artist</th>
                            <th class="px-4 py-2">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row) : ?>
                            <tr class="<?php echo $row['Ranking2'] % 2 == 0 ? 'bg-gray-100' : 'bg-white'; ?>">
                                <td class="px-4 py-2 border"><?php echo htmlentities($row['Ranking2']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlentities($row['Album2']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlentities($row['Artist2']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlentities($row['Year2']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        
        <div class="flex flex-col justify-center">
            <header class="flex flex-col px-20 py-9 w-full bg-gray-600 max-md:px-5 max-md:max-w-full">
                <nav class="flex gap-5 max-md:flex-wrap max-md:max-w-full">
                    <h1 class="flex-auto self-start mt-3.5 text-2xl font-bold text-white">Top Music</h1>
                    <div class="flex flex-col justify-center text-lg text-center text-white whitespace-nowrap">
                        <h1 class="pt-3.5 mt-28 text-lg font-medium text-zinc-100 max-md:mt-10 max-md:max-w-full">
                            © 2024 Copyright. All rights reserved.
                        </h1>
                    </div>
                </nav>
            </header>
        </div>
    </main>
</body>
</html>
