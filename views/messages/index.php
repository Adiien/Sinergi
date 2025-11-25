<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Messages - SINERGI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        /* Custom Scrollbar agar rapi */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden pt-24 flex flex-col">

    <?php require_once 'views/partials/navbar.php'; ?>

    <main class="container mx-auto p-4 flex-1 h-full max-h-[calc(100vh-6rem)]">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-full">
            
            <div class="hidden lg:flex lg:col-span-4 bg-white rounded-2xl shadow-lg flex-col h-full overflow-hidden">
                
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        
                        <div class="flex items-center space-x-3">
                            <a href="<?= BASE_URL ?>/home" class="text-gray-600 hover:text-indigo-600 transition-colors p-1 rounded-full hover:bg-gray-100" title="Back to Home">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </a>
                            <h2 class="text-xl font-bold text-gray-800">Messages</h2>
                        </div>

                        <button class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" placeholder="Find people and groups" class="w-full bg-gray-100 text-gray-700 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-300 text-sm">
                    </div>

                    <div class="flex justify-between mt-4 text-sm font-medium text-gray-500">
                        <button class="text-indigo-600 border-b-2 border-indigo-600 pb-1">All</button>
                        <button class="hover:text-gray-800 pb-1">Unread</button>
                        <button class="hover:text-gray-800 pb-1">Groups</button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll">
                    <?php foreach ($contacts as $contact): ?>
                    <div class="flex items-center px-5 py-4 hover:bg-gray-50 cursor-pointer border-l-4 <?php echo $contact['name'] == 'Ulya Sara' ? 'border-indigo-500 bg-gray-50' : 'border-transparent'; ?>">
                        <div class="relative">
                            <div class="w-12 h-12 <?php echo $contact['avatar_color']; ?> rounded-full flex items-center justify-center text-white font-bold text-lg">
                                <?php echo substr($contact['name'], 0, 1); ?>
                            </div>
                            <?php if($contact['unread']): ?>
                                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-bold text-gray-900"><?php echo $contact['name']; ?></h3>
                                <span class="text-xs text-gray-400"><?php echo $contact['time']; ?></span>
                            </div>
                            <p class="text-sm text-gray-500 truncate font-medium"><?php echo $contact['last_message']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-span-1 lg:col-span-8 bg-white rounded-2xl shadow-lg flex flex-col h-full overflow-hidden">
                
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10">
                    <div class="flex items-center space-x-4">
                        <a href="<?= BASE_URL ?>/home" class="lg:hidden text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        
                        <div class="w-10 h-10 <?php echo $activeChat['user']['avatar_color']; ?> rounded-full flex items-center justify-center text-white font-bold">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900"><?php echo $activeChat['user']['name']; ?></h3>
                            <p class="text-xs text-gray-500"><?php echo $activeChat['user']['handle']; ?></p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scroll p-6 space-y-6 bg-white">
                    
                    <?php foreach ($activeChat['messages'] as $msg): ?>
                        <?php if ($msg['type'] === 'received'): ?>
                            <div class="flex items-end space-x-3">
                                <div class="w-10 h-10 <?php echo $activeChat['user']['avatar_color']; ?> rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </div>
                                <div class="max-w-[70%]">
                                    <div class="bg-[#eff3f8] text-gray-800 px-5 py-3 rounded-2xl rounded-bl-none shadow-sm">
                                        <p class="text-sm leading-relaxed"><?php echo $msg['content']; ?></p>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1 ml-1"><?php echo $msg['time']; ?></p>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="flex items-end justify-end space-x-3">
                                <div class="max-w-[70%]">
                                    <div class="bg-[#36364c] text-white px-5 py-3 rounded-2xl rounded-br-none shadow-md">
                                        <p class="text-sm leading-relaxed"><?php echo $msg['content']; ?></p>
                                    </div>
                                    <div class="flex justify-end items-center mt-1 mr-1 space-x-1">
                                        <p class="text-[10px] text-gray-400"><?php echo $msg['time']; ?></p>
                                        <?php if(isset($msg['read'])): ?>
                                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M5 13l4 4L19 7"></path></svg>
                                            <svg class="w-3 h-3 text-blue-500 -ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>

                <div class="p-4 border-t border-gray-100 bg-white">
                    <div class="flex items-center space-x-3">
                        <button class="text-gray-500 hover:text-gray-700 p-2 bg-gray-100 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </button>
                        <button class="text-gray-500 hover:text-gray-700 p-2 bg-gray-100 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        </button>

                        <div class="flex-1 relative">
                            <input type="text" placeholder="Write a message" class="w-full bg-[#eff3f8] text-gray-700 rounded-full py-3 px-5 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        </div>

                        <button class="text-[#36364c] hover:text-indigo-800">
                            <svg class="w-8 h-8 transform rotate-45" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
</html>