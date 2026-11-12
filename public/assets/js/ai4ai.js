// AI4AI hero background switch: dark (default) or light. The choice is a
// per-browser convenience, kept in localStorage when that is available.
(() => {
  const intro = document.querySelector('.ai4ai-intro');
  const button = intro && intro.querySelector('.ai4ai-theme-toggle');
  if (!intro || !button) return;

  const KEY = 'ai4ai-hero-theme';
  const read = () => {
    try { return localStorage.getItem(KEY); } catch (error) { return null; }
  };
  const save = (value) => {
    try { localStorage.setItem(KEY, value); } catch (error) { /* not stored */ }
  };

  function apply(light) {
    intro.classList.toggle('is-light', light);
    button.setAttribute('aria-pressed', String(light));
  }

  apply(read() === 'light');
  button.hidden = false;
  button.addEventListener('click', () => {
    const light = !intro.classList.contains('is-light');
    apply(light);
    save(light ? 'light' : 'dark');
  });
})();
