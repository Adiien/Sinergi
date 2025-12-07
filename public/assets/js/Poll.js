function submitVote(postId, optionId) {
  fetch("/coba9/post/vote", {
    // Sesuaikan path BASE URL Anda
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      post_id: postId,
      option_id: optionId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Render ulang area poll dengan data baru
        updatePollUI(postId, data.data);
      } else {
        alert(data.message);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function updatePollUI(postId, pollData) {
  const container = document.getElementById(`poll-${postId}`);
  if (!container) return;

  let html = "";
  const totalVotes = pollData.total_votes;

  pollData.options.forEach((opt) => {
    const percent =
      totalVotes > 0 ? Math.round((opt.VOTE_COUNT / totalVotes) * 100) : 0;
    const isMyChoice = pollData.user_voted_option_id == opt.OPTION_ID;

    // Render Progress Bar Style karena user sudah vote
    html += `
        <div class="relative w-full mb-2">
            <div class="relative w-full h-10 bg-gray-100 rounded-lg overflow-hidden border ${
              isMyChoice ? "border-blue-500" : "border-gray-200"
            }">
                <div class="absolute top-0 left-0 h-full bg-blue-200 transition-all duration-500" style="width: ${percent}%;"></div>
                <div class="absolute inset-0 flex items-center justify-between px-4 z-10">
                    <span class="text-sm font-semibold text-gray-800">
                        ${escapeHtml(opt.OPTION_TEXT)}
                        ${
                          isMyChoice
                            ? '<i class="ml-1 text-blue-600">✔</i>'
                            : ""
                        }
                    </span>
                    <span class="text-sm font-bold text-gray-600">${percent}%</span>
                </div>
            </div>
        </div>`;
  });

  html += `<div class="text-xs text-gray-500 mt-2 px-1">Total: ${totalVotes} suara</div>`;

  container.innerHTML = html;
}

function escapeHtml(text) {
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
