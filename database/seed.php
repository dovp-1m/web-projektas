<?php
/**
 * BlogCMS – Database Seeder
 * Run once after schema.sql has been imported:
 *   php seed.php
 *
 * What this script creates:
 *  - 1 admin user
 *  - 99 editor users  (total = 100 users)
 *  - 6 categories
 *  - 10 000 posts spread across categories and users
 */

// ── DB connection ──────────────────────────────────────────
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'blogcms';
$user = getenv('DB_USER') ?: 'blogcms';
$pass = getenv('DB_PASS') ?: 'secret';

$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

echo "Connected to database.\n";

// ── Truncate existing data (safe order) ───────────────────
$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
foreach (['logs','posts','categories','users'] as $t) {
    $pdo->exec("TRUNCATE TABLE `$t`");
}
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");
echo "Tables cleared.\n";

// ── 1. Admin user ─────────────────────────────────────────
$adminHash = password_hash('Admin@12345', PASSWORD_BCRYPT);
$pdo->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)")
    ->execute(['admin','admin@blogcms.local',$adminHash,'admin']);
$adminId = $pdo->lastInsertId();
echo "Admin user created (id=$adminId).\n";

// ── 2. Editor users (99 more, total = 100) ────────────────
$stmtUser = $pdo->prepare(
    "INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)"
);
$editorHash = password_hash('Editor@12345', PASSWORD_BCRYPT);
$editorIds  = [$adminId];
for ($i = 1; $i <= 99; $i++) {
    $stmtUser->execute(["editor$i","editor$i@blogcms.local",$editorHash,'editor']);
    $editorIds[] = (int)$pdo->lastInsertId();
}
echo "99 editor users created (total users = 100).\n";

// ── 3. Categories ─────────────────────────────────────────
$cats = [
    ['Technology', 'technology', 'Articles about tech and software', '#0d6efd'],
    ['Science',    'science',    'Scientific discoveries and research', '#198754'],
    ['Travel',     'travel',     'Destinations and travel tips',        '#fd7e14'],
    ['Health',     'health',     'Wellness, fitness, and nutrition',    '#dc3545'],
    ['Business',   'business',  'Entrepreneurship and economy',        '#6f42c1'],
    ['Culture',    'culture',    'Art, music, literature',              '#20c997'],
];
$stmtCat  = $pdo->prepare("INSERT INTO categories (name,slug,description,color) VALUES (?,?,?,?)");
$catIds   = [];
foreach ($cats as $c) {
    $stmtCat->execute($c);
    $catIds[] = (int)$pdo->lastInsertId();
}
echo "6 categories created.\n";

// ── 4. Posts (10 000) ─────────────────────────────────────
$words = ['lorem','ipsum','dolor','sit','amet','consectetur','adipiscing','elit',
          'sed','eiusmod','tempor','incididunt','labore','dolore','magna','aliqua',
          'enim','ad','minim','veniam','quis','nostrud','exercitation','ullamco',
          'laboris','nisi','aliquip','commodo','consequat','duis','aute','irure'];

function randWords(array $w, int $n): string {
    shuffle($w);
    return implode(' ', array_slice($w, 0, $n));
}

function makeBody(array $w): string {
    $paragraphs = [];
    for ($p = 0; $p < rand(3, 6); $p++) {
        $sentences = [];
        for ($s = 0; $s < rand(3, 7); $s++) {
            $sentences[] = ucfirst(randWords($w, rand(8, 20))) . '.';
        }
        $paragraphs[] = implode(' ', $sentences);
    }
    return implode("\n\n", $paragraphs);
}

$stmtPost = $pdo->prepare("
    INSERT INTO posts
        (title, slug, body, excerpt, category_id, user_id, status, featured_image, views, published_at)
    VALUES
        (?,?,?,?,?,?,?,?,?,?)
");

$statuses    = ['published','published','published','draft'];   // 75 % published
$usedSlugs   = [];
$batchSize   = 500;
$pdo->beginTransaction();

for ($i = 1; $i <= 10000; $i++) {
    $title   = ucfirst(randWords($words, rand(4, 9))) . ' ' . $i;
    $slug    = strtolower(preg_replace('/[^a-z0-9]+/', '-', $title)) . '-' . $i;
    $body    = makeBody($words);
    $excerpt = implode(' ', array_slice(explode(' ', strip_tags($body)), 0, 25)) . '…';
    $catId   = $catIds[array_rand($catIds)];
    $userId  = $editorIds[array_rand($editorIds)];
    $status  = $statuses[array_rand($statuses)];
    $views   = rand(0, 9999);
    $pubAt   = $status === 'published'
        ? date('Y-m-d H:i:s', strtotime("-" . rand(1, 730) . " days"))
        : null;

    $stmtPost->execute([$title, $slug, $body, $excerpt, $catId, $userId,
                        $status, null, $views, $pubAt]);

    if ($i % $batchSize === 0) {
        $pdo->commit();
        $pdo->beginTransaction();
        echo "  Inserted $i / 10000 posts…\n";
    }
}
$pdo->commit();
echo "10 000 posts created.\n";

// ── 5. Sample log entries ─────────────────────────────────
$logs = [
    ['INFO',    'Auth_controller',     'login',         'Admin logged in successfully', $adminId],
    ['INFO',    'Post_model',          'create',        'New post created by admin',    $adminId],
    ['WARNING', 'Post_controller',     'store',         'Validation failed on post form', null],
    ['ERROR',   'Category_controller','destroy',        'Attempt to delete non-existent category', null],
    ['INFO',    'Auth_controller',     'logout',        'User session ended',           $adminId],
];
$stmtLog = $pdo->prepare(
    "INSERT INTO logs (level,class_name,method_name,message,user_id,ip_address) VALUES (?,?,?,?,?,?)"
);
foreach ($logs as $l) {
    $stmtLog->execute([$l[0],$l[1],$l[2],$l[3],$l[4],'127.0.0.1']);
}
echo "5 sample log entries created.\n";

echo "\n✅  Seeding complete!\n";
echo "   Admin login:  admin / Admin@12345\n";
echo "   Editor login: editor1 / Editor@12345\n";
