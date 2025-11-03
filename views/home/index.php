<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?= BASE_URL ?>/assets/css/output.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="bg-gray-100">
    <nav class="bg-[#36364c] p-4 shadow-lg">
      <div
        class="container mx-auto px-6 py-3 flex justify-between items-center"
      >
        <a href="#" class="flex items-center space-x-3">
          <div class="bg-white rounded-full p-1.5">
            <span class="text-[#36364c] text-xs font-bold">LOGO</span>
          </div>
          <span class="text-white text-xl tracking-widest font-azeret"
            >SINERGI</span
          >
        </a>

        <div class="relative w-1/3">
          <input
            type="text"
            class="bg-gray-100 rounded-lg py-2 px-4 pl-10 w-full focus:outline-none"
            placeholder="Search...."
          />
          <svg
            class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
            />
          </svg>
        </div>

        <div class="flex items-center space-x-6">
          <a
            href="#"
            class="text-white font-semibold border-b-2 border-white pb-1"
            >Home</a
          >
          <a href="#" class="text-gray-300 hover:text-white">Discussion</a>

          <a
            href="#"
            class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium"
          >
            <img src="" alt="" class="w-6 h-6" />
          </a>
          <a
            href="#"
            class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium"
          >
            <img src="" alt="" class="w-6 h-6" />
          </a>
          <div
            class="w-9 h-9 bg-white rounded-full flex items-center justify-center font-bold text-indigo-900 overflow-hidden"
          >
            <svg
              class="w-8 h-8 text-gray-400"
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"
              />
            </svg>
          </div>
        </div>
      </div>
    </nav>

    <main class="container mx-auto p-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
      <aside class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-5 text-center">
          <div
            class="w-20 h-20 bg-green-500 rounded-full mx-auto flex items-center justify-center text-white text-4xl font-bold mb-3"
          >
            A
          </div>
          <h2 class="text-lg font-bold text-gray-900">Adien Fathikurahman</h2>
          <p class="text-sm text-gray-500">@adien.fathikurahman</p>
          <div class="flex justify-around mt-4 pt-4 border-t">
            <div class="text-center">
              <span class="font-bold text-gray-900">0</span>
              <p class="text-sm text-gray-500">Followers</p>
            </div>
            <div class="text-center">
              <span class="font-bold text-gray-900">0</span>
              <p class="text-sm text-gray-500">Following</p>
            </div>
            <div class="text-center">
              <span class="font-bold text-gray-900">0</span>
              <p class="text-sm text-gray-500">Posts</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-5">
          <nav class="space-y-4">
            <a
              href="#"
              class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium"
            >
              <svg
                class="w-6 h-6 text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-4.682-2.72a3 3 0 00-4.682 2.72m4.682-2.72V18.72m0 0A18.182 18.182 0 015.25 12.75m12.999 2.243A18.182 18.182 0 0112 15m11.25-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <span>Groups</span>
            </a>
            <a
              href="#"
              class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium"
            >
              <svg
                class="w-6 h-6 text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0111.186 0z"
                />
              </svg>
              <span>Save Items</span>
            </a>
            <a
              href="#"
              class="flex items-center space-x-3 text-gray-700 hover:text-indigo-600 font-medium"
            >
              <svg
                class="w-6 h-6 text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443h2.282c1.584 0 2.863-1.39 2.863-3.227V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"
                />
              </svg>
              <span>Messages</span>
            </a>
          </nav>
        </div>
      </aside>

      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-5">
          <div class="flex space-x-4 border-b pb-4 mb-4">
            <button class="font-medium text-gray-700">Create Post</button>
            <button class="font-medium text-gray-500 hover:text-gray-700">
              Create Poll
            </button>
          </div>
          <div>
            <textarea
              class="w-full border-none rounded-lg p-3"
              rows="3"
              placeholder="Write here..."
            ></textarea>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="p-5 flex justify-between items-center">
            <div class="flex items-center space-x-3">
              <div
                class="w-12 h-12 bg-red-400 rounded-full flex items-center justify-center"
              >
                <svg
                  class="w-8 h-8 text-white"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"
                  />
                </svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900">Username</h3>
                <p class="text-sm text-gray-500">@username</p>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <button
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-medium"
              >
                Follow
              </button>
              <button class="text-gray-400 hover:text-gray-600">
                <svg
                  class="w-5 h-5"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
                  />
                </svg>
              </button>
            </div>
          </div>

          <div class="relative">
            <img
              src="https://via.placeholder.com/600x200.png?text=Code+Snippet+Image"
              alt="Group Banner"
              class="w-full h-48 object-cover"
            />
            <div
              class="absolute bottom-0 left-5 transform translate-y-1/2 w-16 h-16 bg-gray-600 rounded-xl border-4 border-white flex items-center justify-center"
            >
              <svg
                class="w-10 h-10 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-4.682-2.72a3 3 0 00-4.682 2.72m4.682-2.72V18.72m0 0A18.182 18.182 0 015.25 12.75m12.999 2.243A18.182 18.182 0 0112 15m11.25-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
          </div>

          <div class="pt-12 px-5 pb-3">
            <h2 class="text-xl font-bold text-gray-900">Group Name</h2>
          </div>

          <div class="px-5 pb-4 text-sm text-gray-500 border-b">
            <span>0 Likes</span> - <span>0 Comments</span>
          </div>

          <div class="p-3 flex space-x-2">
            <button
              class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 font-medium px-3 py-2 rounded-lg hover:bg-gray-100"
            >
              <svg
                class="w-5 h-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.25a.75.75 0 01-.75-.75V10.5c0-.414.336-.75.75-.75h1.383z"
                />
              </svg>
              <span>Like</span>
            </button>
          </div>
        </div>
      </div>

      <aside class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-5">
          <h3 class="font-bold text-gray-900 text-lg mb-4">People to Follow</h3>

          <div class="space-y-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-300 rounded-full"></div>
                <div>
                  <h4 class="font-semibold text-gray-900 text-sm">Ulya Sara</h4>
                  <p class="text-xs text-gray-500">@ulyasara</p>
                </div>
              </div>
              <button
                class="border border-blue-500 text-blue-500 hover:bg-blue-50 px-3 py-1 rounded-full text-sm font-medium"
              >
                Follow
              </button>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-300 rounded-full"></div>
                <div>
                  <h4 class="font-semibold text-gray-900 text-sm">
                    Ahmad Faris
                  </h4>
                  <p class="text-xs text-gray-500">@ahmadfaris</p>
                </div>
              </div>
              <button
                class="border border-blue-500 text-blue-500 hover:bg-blue-50 px-3 py-1 rounded-full text-sm font-medium"
              >
                Follow
              </button>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-300 rounded-full"></div>
                <div>
                  <h4 class="font-semibold text-gray-900 text-sm">
                    Muhammad Nur
                  </h4>
                  <p class="text-xs text-gray-500">@muhammadnur</p>
                </div>
              </div>
              <button
                class="border border-blue-500 text-blue-500 hover:bg-blue-50 px-3 py-1 rounded-full text-sm font-medium"
              >
                Follow
              </button>
            </div>
          </div>

          <div class="mt-5 pt-4 border-t">
            <a href="#" class="text-blue-600 font-medium text-sm">See All</a>
          </div>
        </div>
      </aside>
    </main>
  </body>
</html>
