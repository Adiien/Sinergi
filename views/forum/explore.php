<!-- views/forum/explore.php -->
<!DOCTYPE html>
<html lang="en">
<head>...</head>
<body>
<?php require_once 'views/partials/navbar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Explore Forums</h2>
        <a href="<?= BASE_URL ?>/forum" class="text-sm font-bold text-blue-600 hover:underline">
            Back to My Forums
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php if (!empty($forums)): ?>
            <?php foreach ($forums as $forum): ?>
                <!-- pakai card yang sama seperti di My Forums -->
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                <p class="text-lg font-medium">No forums found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
