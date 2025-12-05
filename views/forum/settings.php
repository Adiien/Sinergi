<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings: <?= htmlspecialchars($forum['NAME']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eff3f8;
        }
    </style>
</head>

<body class="pt-24 pb-10">

    <?php require_once 'views/partials/navbar.php'; ?>

    <div class="container mx-auto px-4 max-w-2xl">

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Forum Settings</h1>
            <a href="<?= BASE_URL ?>/forum/show?id=<?= $forum['FORUM_ID'] ?>" class="text-sm text-gray-500 hover:text-indigo-600 font-medium">
                &larr; Back to Forum
            </a>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['error_message'];
                unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm p-8">
            <form action="<?= BASE_URL ?>/forum/update" method="POST" enctype="multipart/form-data" class="space-y-6">

                <input type="hidden" name="forum_id" value="<?= $forum['FORUM_ID'] ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                    <div class="relative w-full h-40 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 mb-3 group">
                        <?php if (!empty($forum['COVER_IMAGE'])): ?>
                            <img src="<?= BASE_URL ?>/public/uploads/forums/<?= $forum['COVER_IMAGE'] ?>" class="w-full h-full object-cover" id="cover-preview">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50" id="cover-placeholder">
                                No Cover Image
                            </div>
                            <img src="" class="hidden w-full h-full object-cover" id="cover-preview">
                        <?php endif; ?>
                    </div>
                    <input type="file" name="cover_image" id="cover_input" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forum Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($forum['NAME']) ?>" required
                        class="w-full rounded-lg border-gray-300 border p-2.5 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full rounded-lg border-gray-300 border p-2.5 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($forum['DESCRIPTION']) ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visibility</label>
                    <div class="flex gap-4">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1 <?= strtolower($forum['VISIBILITY']) == 'public' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' ?>">
                            <input type="radio" name="visibility" value="public" class="text-indigo-600 focus:ring-indigo-500" <?= strtolower($forum['VISIBILITY']) == 'public' ? 'checked' : '' ?>>
                            <span class="ml-2 text-sm font-medium text-gray-900">Public</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1 <?= strtolower($forum['VISIBILITY']) == 'private' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' ?>">
                            <input type="radio" name="visibility" value="private" class="text-indigo-600 focus:ring-indigo-500" <?= strtolower($forum['VISIBILITY']) == 'private' ? 'checked' : '' ?>>
                            <span class="ml-2 text-sm font-medium text-gray-900">Private</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-indigo-700 transition shadow-md">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Simple Image Preview Script
        const coverInput = document.getElementById('cover_input');
        const coverPreview = document.getElementById('cover-preview');
        const coverPlaceholder = document.getElementById('cover-placeholder');

        coverInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    coverPreview.src = e.target.result;
                    coverPreview.classList.remove('hidden');
                    if (coverPlaceholder) coverPlaceholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>