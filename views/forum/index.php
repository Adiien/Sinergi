<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forums - SINERGI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="bg-[#eff3f8] h-screen flex flex-col overflow-hidden">

    <div class="z-50 relative">
        <?php require_once 'views/partials/navbar.php'; ?>
    </div>

    <div class="flex flex-1 overflow-hidden pt-20">
        
        <aside class="w-80 bg-white flex flex-col h-full z-10 hidden lg:flex rounded-tr-[30px] shadow-sm relative">
            <div class="p-8 flex flex-col h-full">
                <h1 class="text-3xl font-extrabold text-[#1e293b] mb-6 tracking-tight">Forums</h1>
                
                <div class="relative mb-6">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" placeholder="Search forums..." class="w-full bg-[#f3f4f6] text-gray-700 rounded-full py-3 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-100 transition text-sm font-medium">
                </div>

                <nav class="space-y-2 mb-8">
                    <a href="#" class="flex items-center px-2 py-2 text-gray-600 hover:text-[#1e293b] font-bold transition group">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-gray-200 transition">
                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                        </div>
                        Your posts
                    </a>
                    <a href="#" class="flex items-center px-2 py-2 text-gray-600 hover:text-[#1e293b] font-bold transition group">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 group-hover:bg-gray-200 transition">
                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </div>
                        Your forums
                    </a>
                </nav>

                <button id="btn-trigger-create" class="cursor-pointer w-full bg-[#e0e7ff] hover:bg-[#dbeafe] text-[#4338ca] font-bold py-3 px-4 rounded-xl flex items-center justify-center transition mb-8 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create New Forum
                </button>

                <div class="flex items-center justify-between mb-4 border-t pt-4 border-gray-100">
                    <h3 class="text-sm font-bold text-gray-600">Forums you've joined</h3>
                    <a href="#" class="text-xs text-blue-600 font-semibold hover:underline">See All</a>
                </div>

                <div class="space-y-4 overflow-y-auto custom-scroll pr-2 flex-1">
                    <?php if(!empty($joinedForums)): ?>
                        <?php foreach($joinedForums as $jf): ?>
                        <div class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded-xl transition group">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                <?= substr($jf['NAME'], 0, 1) ?>
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="text-sm font-bold text-gray-800 leading-tight truncate group-hover:text-blue-600 transition"><?= htmlspecialchars($jf['NAME']) ?></h4>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                         <div class="flex flex-col items-center justify-center py-6 text-center opacity-60">
                             <p class="text-xs text-gray-500">You haven't joined any forums yet.</p>
                         </div>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto custom-scroll relative">
            <div class="max-w-7xl mx-auto">
                
                <div id="alert-container">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                            <p class="font-bold">Success</p>
                            <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                            <p class="font-bold">Error</p>
                            <p><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div id="view-forum-list" class="fade-in">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">My Forums</h2>
                        </div>
                        <a href="<?= BASE_URL ?>views/forum/explore" 
                            class="text-sm font-bold text-blue-600 hover:underline flex items-center">
                            Explore Forums
                        </a>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <?php if(!empty($myForums)): ?>
                            <?php foreach($myForums as $forum): ?>
                            <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-[280px]">
                                <div class="h-36 relative overflow-hidden bg-gray-200">
                                    <?php if(!empty($forum['COVER_IMAGE'])): ?>
                                        <img src="<?= BASE_URL ?>/public/uploads/forums/<?= $forum['COVER_IMAGE'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-r from-blue-400 to-indigo-500 group-hover:scale-105 transition-transform duration-500"></div>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/0 transition"></div>
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-[10px] font-bold text-gray-700 shadow-sm">
                                        <?= isset($forum['VISIBILITY']) && $forum['VISIBILITY'] == 'private' ? 'Private' : 'Public' ?>
                                    </div>
                                </div>
                                <div class="p-5 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800 leading-tight mb-1 truncate"><?= htmlspecialchars($forum['NAME']) ?></h3>
                                        <p class="text-xs text-gray-500"><?= $forum['MEMBER_COUNT'] ?? 0 ?> Members &bull; Active</p>
                                    </div>
                                    <button class="w-full mt-4 border border-gray-200 bg-gray-50 text-gray-700 font-bold py-2 rounded-xl hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition text-sm">
                                        Open Forum
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-span-full flex flex-col items-center justify-center py-20 text-gray-400">
                                <p class="text-lg font-medium">No forums found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="view-create-forum" class="hidden fade-in">
                    <div class="flex justify-center">
                        <div class="bg-white rounded-[30px] shadow-sm border border-gray-100 p-10 w-full max-w-2xl relative">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-extrabold text-[#1e293b]">Create Forum</h2>
                            </div>
                            <div class="border-b border-gray-100 -mx-10 mb-8"></div>

                            <form action="<?= BASE_URL ?>/forum/create" method="POST">
                                <div class="mb-8">
                                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-gray-800 text-sm font-medium" placeholder="Forum Name" required autocomplete="off">
                                </div>
                                <div class="hidden">
                                    <input type="text" name="description" value="New Classroom">
                                </div>

                                <div class="mb-10">
                                    <label class="block text-[#1e293b] font-bold mb-4 text-sm">Forum Visibility</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="visibility" value="public" class="peer sr-only" checked>
                                            <div class="border border-gray-300 rounded-xl p-5 flex items-center space-x-4 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-[#f8fafc] transition-all h-full">
                                                <div class="flex-shrink-0 text-gray-400 peer-checked:text-[#1e293b]">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                                </div>
                                                <div>
                                                    <span class="block font-bold text-[#1e293b] text-sm">Public</span>
                                                    <span class="block text-[11px] text-gray-400 font-medium mt-0.5">Discoverable to all</span>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="cursor-pointer group relative">
                                            <input type="radio" name="visibility" value="private" class="peer sr-only">
                                            <div class="border border-gray-300 rounded-xl p-5 flex items-center space-x-4 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-[#f8fafc] transition-all h-full">
                                                <div class="flex-shrink-0 text-gray-400 peer-checked:text-[#1e293b]">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                                                </div>
                                                <div>
                                                    <span class="block font-bold text-[#1e293b] text-sm">Private</span>
                                                    <span class="block text-[11px] text-gray-400 font-medium mt-0.5">Discoverable only to member</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end items-center space-x-4 mt-8">
                                    <button type="button" id="btn-cancel-create" class="text-gray-500 font-semibold text-sm hover:text-gray-800 px-4 py-2 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="bg-[#0f62fe] hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 text-sm">
                                        Save & Continue
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const viewList = document.getElementById('view-forum-list');
        const viewCreate = document.getElementById('view-create-forum');
        const btnTrigger = document.getElementById('btn-trigger-create');
        const btnCancel = document.getElementById('btn-cancel-create');

        console.log("JS Loaded. Trigger Button:", btnTrigger);

        function showCreateForm() {
            console.log("Switching to Create Form");
            viewList.classList.add('hidden');
            viewCreate.classList.remove('hidden');
        }

        function showList() {
            console.log("Switching back to List");
            viewCreate.classList.add('hidden');
            viewList.classList.remove('hidden');
        }

        if(btnTrigger) {
            btnTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                showCreateForm();
            });
        } else {
            console.error("Button Trigger Create NOT FOUND!");
        }

        if(btnCancel) {
            btnCancel.addEventListener('click', (e) => {
                e.preventDefault();
                showList();
            });
        }
    });
    </script>
</body>
</html>