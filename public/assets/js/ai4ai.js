(() => {
  const video = document.querySelector('.ai4ai-stage-video');
  const toggle = document.querySelector('.ai4ai-motion-toggle');
  if (!video || !toggle) return;

  const root = document.documentElement;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  let failed = false;
  let userPaused = false;
  const motionDisabled = () => reducedMotion.matches
    || root.classList.contains('a11y-no-motion')
    || root.classList.contains('a11y-hide-images');

  function updateButton() {
    const action = video.paused ? 'Play' : 'Pause';
    toggle.textContent = action;
    toggle.setAttribute('aria-label', action + ' AI4AI animation');
  }

  function sync() {
    toggle.hidden = failed || motionDisabled();
    if (toggle.hidden) {
      video.pause();
      video.classList.remove('is-visible');
      return;
    }
    if (userPaused) return;
    if (!video.hasAttribute('src')) video.src = video.dataset.src;
    if (video.paused) video.play().catch(updateButton);
  }

  toggle.addEventListener('click', () => {
    userPaused = !video.paused;
    if (userPaused) video.pause();
    else sync();
  });
  video.addEventListener('playing', () => {
    if (!motionDisabled()) video.classList.add('is-visible');
    updateButton();
  });
  video.addEventListener('pause', updateButton);
  video.addEventListener('error', () => {
    failed = true;
    sync();
  });
  reducedMotion.addEventListener('change', sync);
  new MutationObserver(sync).observe(root, { attributeFilter: ['class'] });
  sync();
})();
