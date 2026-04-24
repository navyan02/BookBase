<?php
// Batch fetch covers for books without a stored cover and save locally
chdir(__DIR__ . '/..'); // move to app root
require 'db.php';

if (!is_dir(__DIR__ . '/../uploads')) {
    mkdir(__DIR__ . '/../uploads', 0755, true);
}

$sql = "SELECT BookID, Title, AuthorID, CoverImage FROM Book WHERE CoverImage IS NULL OR CoverImage = '' OR CoverImage LIKE 'https://picsum.photos/%'";
$res = $conn->query($sql);
if (!$res) {
    echo "Query failed: " . $conn->error . PHP_EOL;
    exit(1);
}

function fetch_openlibrary_cover($title, $authorName) {
    $url = "https://openlibrary.org/search.json?title=" . urlencode($title) . "&author=" . urlencode($authorName) . "&limit=1";
    $ctx = stream_context_create(['http' => ['timeout' => 5]]);
    $resp = @file_get_contents($url, false, $ctx);
    if (!$resp) return null;
    $data = json_decode($resp, true);
    if (empty($data['docs'][0])) return null;
    $doc = $data['docs'][0];
    if (!empty($doc['cover_i'])) {
        return 'https://covers.openlibrary.org/b/id/' . intval($doc['cover_i']) . '-L.jpg';
    }
    if (!empty($doc['isbn'][0])) {
        return 'https://covers.openlibrary.org/b/isbn/' . urlencode($doc['isbn'][0]) . '-L.jpg';
    }
    return null;
}

while ($row = $res->fetch_assoc()) {
    $bookId = $row['BookID'];
    // get author name
    $a = $conn->query("SELECT Name FROM Author WHERE AuthorID = " . intval($row['AuthorID']))->fetch_assoc();
    $authorName = $a['Name'] ?? '';

    echo "Processing BookID={$bookId} - {$row['Title']}\n";
    $coverUrl = fetch_openlibrary_cover($row['Title'], $authorName);
    if (!$coverUrl) {
        echo "  No cover found on Open Library\n";
        continue;
    }
    echo "  Found remote cover: $coverUrl\n";
    // attempt to download
    $ctx = stream_context_create(['http' => ['timeout' => 10]]);
    $img = @file_get_contents($coverUrl, false, $ctx);
    if ($img === false) {
        echo "  Failed to download remote image, will store remote URL instead.\n";
        $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
        $upd->bind_param('si', $coverUrl, $bookId);
        $upd->execute();
        continue;
    }
    // detect extension from content-type header fallback to jpg
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_buffer($finfo, $img);
    finfo_close($finfo);
    $ext = 'jpg';
    if ($mime === 'image/png') $ext = 'png';
    if ($mime === 'image/webp') $ext = 'webp';

    $filename = 'cover_' . uniqid() . '.' . $ext;
    $path = __DIR__ . '/../uploads/' . $filename;
    if (file_put_contents($path, $img) === false) {
        echo "  Failed to save image locally\n";
        continue;
    }
    $local = 'uploads/' . $filename;
    $upd = $conn->prepare("UPDATE Book SET CoverImage = ? WHERE BookID = ?");
    $upd->bind_param('si', $local, $bookId);
    $upd->execute();
    echo "  Saved as $local\n";
}

echo "Done.\n";
