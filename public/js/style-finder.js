(() => {
  const dropZone          = document.getElementById('drop-zone');
  const fileInput         = document.getElementById('file-input');
  const dropContent       = document.getElementById('drop-content');
  const previewWrapper    = document.getElementById('preview-wrapper');
  const previewImg        = document.getElementById('preview-img');
  const analyzeBtnWrapper = document.getElementById('analyze-btn-wrapper');
  const chooseBtn         = document.getElementById('choose-btn');
  const removeBtn         = document.getElementById('remove-btn');
  const analyzeBtn        = document.getElementById('analyze-btn');
  const ctaScroll         = document.getElementById('cta-scroll');

  if (!dropZone) return;

  // ── Ouvre le sélecteur de fichier ──
  dropZone.addEventListener('click', () => fileInput.click());
  chooseBtn.addEventListener('click', e => { e.stopPropagation(); fileInput.click(); });

  // ── Sélection via input ──
  fileInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) showPreview(file);
  });

  // ── Drag & Drop ──
  dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('border-gray-600', 'bg-[#f0ece6]');
  });
  dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-gray-600', 'bg-[#f0ece6]');
  });
  dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-gray-600', 'bg-[#f0ece6]');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) showPreview(file);
  });

  // ── Preview ──
  function showPreview(file) {
    const reader = new FileReader();
    reader.onload = e => {
      previewImg.src = e.target.result;
      dropContent.classList.add('hidden');
      previewWrapper.classList.remove('hidden');
      previewWrapper.classList.add('flex');
      analyzeBtnWrapper.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }

  // ── Suppression ──
  removeBtn.addEventListener('click', e => {
    e.stopPropagation();
    previewImg.src = '';
    previewWrapper.classList.add('hidden');
    previewWrapper.classList.remove('flex');
    dropContent.classList.remove('hidden');
    analyzeBtnWrapper.classList.add('hidden');
    fileInput.value = '';
  });

  // ── Analyser (logique IA à brancher plus tard) ──
  analyzeBtn.addEventListener('click', () => {
    const dataUrl = previewImg.src;
    if (dataUrl && dataUrl.startsWith('data:')) {
      sessionStorage.setItem('ledressing_scan_image', dataUrl);
      // TODO: rediriger vers la page résultats quand l'IA sera branchée
      // window.location.href = '/style-finder/resultats';
    }
  });

  // ── CTA scroll vers le haut ──
  ctaScroll?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();