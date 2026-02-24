export function navScroll() {
  const navBar = document.getElementById("navbar");
  let lastScroll = 0;

  window.addEventListener("scroll", () => {
    const currentScroll = window.scrollY;

    // En dessous de 700px de scroll, la navbar reste toujours visible
    if (currentScroll < 700) return;

    if (currentScroll > lastScroll) {
      // L'utilisateur scroll vers le bas → on cache la navbar
      navBar.classList.add("-translate-y-full");
    } else {
      // L'utilisateur scroll vers le haut → on réaffiche la navbar
      navBar.classList.remove("-translate-y-full");
    }

    // Met à jour la position de référence pour le prochain événement scroll
    lastScroll = currentScroll;
  });
}
