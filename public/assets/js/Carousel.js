function slideCarousel(postId, direction) {
  const container = document.getElementById(`carousel-${postId}`);

  if (container) {
    // Ambil lebar container (lebar 1 gambar)
    const scrollAmount = container.clientWidth;

    if (direction === "left") {
      container.scrollBy({
        left: -scrollAmount,
        behavior: "smooth",
      });
    } else {
      container.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });
    }
  }
}
