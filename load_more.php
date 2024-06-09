<?php
require_once("sparqllib.php");

$offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 50;
$search = isset($_POST['search']) ? $_POST['search'] : '';

$query = "
    PREFIX owl: <http://www.w3.org/2002/07/owl#>
    PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
    PREFIX ex: <http://example.org/>
    PREFIX id: <https://songs.com/>
    PREFIX item: <https://songs.com/ns/item#>
    PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

    SELECT ?Album ?Artist ?Ranking 
    WHERE
    { 
        ?items
            ex:Album ?Album ;
            ex:Artist ?Artist ;
            ex:Ranking ?Ranking .
        " . ($search ? "FILTER (regex(?Album, '$search', 'i') || regex(?Artist, '$search', 'i'))" : "") . "
    }
    ORDER BY ASC(?Ranking)
    LIMIT $limit OFFSET $offset
";

$data = sparql_get("http://localhost:3030/music", $query);

if (!isset($data)) {
    echo "<p>Error: " . sparql_errno() . ": " . sparql_error() . "</p>";
    exit;
}

foreach ($data as $row) {
    echo "<tr class='" . ($row['Ranking'] % 2 == 0 ? 'bg-gray-100' : 'bg-white') . "'>";
    echo "<td class='px-4 py-2 border'>" . htmlentities($row['Ranking']) . "</td>";
    echo "<td class='px-4 py-2 border'>" . htmlentities($row['Album']) . "</td>";
    echo "<td class='px-4 py-2 border'>" . htmlentities($row['Artist']) . "</td>";
    echo "</tr>";
}
