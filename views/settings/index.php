<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SINERGI</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-[#eff3f8] pt-32 pb-10">

    <?php require_once 'views/partials/navbar.php'; ?>

    <main class="container mx-auto px-4 lg:px-8">
        <button class="flex items-center text-indigo-600 hover:text-indigo-800 mb-6 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            <span class="font-medium">Back</span>
        </button>


        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-28">
                    <nav class="flex flex-col py-2">
                        <a href="#" class="flex items-center px-6 py-3.5 bg-blue-50 text-blue-700 font-bold border-l-4 border-blue-600 transition group">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Personal Info
                        </a>
                        <a href="#" class="flex items-center px-6 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition group border-l-4 border-transparent">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Login & Security
                        </a>
                        <a href="#" class="flex items-center px-6 py-3.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition group border-l-4 border-transparent">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            Notifications
                        </a>
                    </nav>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">

                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Personal Information</h2>

                    <div class="space-y-8">

                        <div class="flex items-center justify-between group">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <img src="<?= $userData['profile_picture'] ?>" alt="Profile" class="w-16 h-16 rounded-full object-cover ring-2 ring-gray-100">
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Profile Photo</h3>
                                    <p class="text-sm text-gray-500">Customize how you appear to others</p>
                                </div>
                            </div>
                            <button class="text-sm font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition">
                                Update
                            </button>
                        </div>

                        <div class="flex items-center justify-between group py-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Full Name</h3>
                                <p class="text-gray-600 mt-1"><?= $userData['name'] ?></p>
                            </div>
                            <button onclick="openEditModal('name')" class="text-gray-400 hover:text-indigo-600 p-2 rounded-full hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center justify-between group py-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Email Address</h3>
                                <div class="flex items-center space-x-2 mt-1">
                                    <p class="text-gray-600"><?= $userData['email'] ?></p>
                                    <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Verified</span>
                                </div>
                            </div>
                            <button class="cursor-not-allowed text-gray-300 p-2" title="Email cannot be changed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="flex items-center justify-between group py-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Identity Number (NIM/NIP)</h3>
                                <p class="text-gray-600 mt-1"><?= $userData['nim_nip'] ?></p>
                            </div>
                            <div class="text-xs text-gray-400 font-medium bg-gray-50 px-3 py-1 rounded-full">
                                Permanent
                            </div>
                        </div>

                        <div class="flex items-center justify-between group py-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Account Type</h3>
                                <p class="text-gray-600 mt-1"><?= $userData['status'] ?></p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-8 border border-red-100">
                    <h2 class="text-lg font-bold text-red-600 mb-4">Danger Zone</h2>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">Once you delete your account, there is no going back. Please be certain.</p>
                        <button class="text-sm font-bold text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 px-5 py-2.5 rounded-lg transition">
                            Delete Account
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div id="editModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all scale-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Edit Profile</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">New Value</label>
                    <input type="text"
                        id="editInput"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none text-gray-800"
                        placeholder="Enter value" required>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button"
                        onclick="closeEditModal()"
                        class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentEditField = '';

        function openEditModal(type) {
            currentEditField = type;
            const modal = document.getElementById('editModal');
            const input = document.getElementById('editInput');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
            }, 10);

            input.value = '';

            if (type === 'name') {
                input.placeholder = "Enter your full name";
            }

            input.focus();
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.firstElementChild.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
            currentEditField = '';
        }

        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const newValue = document.getElementById('editInput').value;
            const submitBtn = this.querySelector('button[type="submit"]');

            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Saving...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            fetch('<?= BASE_URL ?>/settings/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        field: currentEditField,
                        value: newValue
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    closeEditModal();
                });
        });

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
    <script src="<?= BASE_URL ?>/public/assets/js/Notification.js"></script>

</body>

</html>