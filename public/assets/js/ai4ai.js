(() => {
  const stage = document.querySelector('.ai4ai-stage');
  const toggle = stage && stage.querySelector('.ai4ai-motion-toggle');
  const rig = stage && stage.querySelector('.ai4ai-rig');
  if (!stage || !toggle || !rig) return;

  const root = document.documentElement;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');
  const pointerArea = stage.closest('.ai4ai-intro') || stage;
  let userPaused = false;
  let onScreen = true;
  let frame = 0;
  let target = { x: 0, y: 0 };

  const motionDisabled = () => reducedMotion.matches || root.classList.contains('a11y-no-motion');
  const playing = () => !motionDisabled() && !userPaused;

  function tilt(x, y) {
    rig.style.setProperty('--ry', (x * 7).toFixed(2));
    rig.style.setProperty('--rx', (y * 4).toFixed(2));
  }

  function sync() {
    const disabled = motionDisabled();
    toggle.hidden = disabled;
    stage.classList.toggle('is-paused', !disabled && (userPaused || !onScreen));
    const action = userPaused ? 'Play' : 'Pause';
    toggle.textContent = action;
    toggle.setAttribute('aria-label', action + ' AI4AI animation');
    if (!playing()) tilt(0, 0);
  }

  toggle.addEventListener('click', () => {
    userPaused = !userPaused;
    sync();
  });

  // Pointer parallax: a small extra turn of the camera towards the pointer.
  pointerArea.addEventListener('pointermove', (event) => {
    if (!finePointer.matches || !playing() || event.pointerType !== 'mouse') return;
    const box = stage.getBoundingClientRect();
    const clamp = (value) => Math.max(-1, Math.min(1, value));
    target = {
      x: clamp((event.clientX - (box.left + box.width / 2)) / box.width),
      y: clamp((event.clientY - (box.top + box.height / 2)) / box.height),
    };
    if (!frame) {
      frame = requestAnimationFrame(() => {
        frame = 0;
        if (playing()) tilt(target.x, target.y);
      });
    }
  });
  pointerArea.addEventListener('pointerleave', () => tilt(0, 0));

  // Stop the idle camera while the hero is scrolled out of view.
  if ('IntersectionObserver' in window) {
    new IntersectionObserver((entries) => {
      onScreen = entries[entries.length - 1].isIntersecting;
      sync();
    }).observe(stage);
  }

  reducedMotion.addEventListener('change', sync);
  new MutationObserver(sync).observe(root, { attributeFilter: ['class'] });
  sync();
})();
