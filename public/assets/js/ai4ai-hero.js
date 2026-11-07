/*
 * AI4AI hero: the nine-path cube.
 *
 * A 3x3 cube, glazed in nine colours (one per learner group), scrambles,
 * bursts into 216 pieces and rebuilds itself as the word AI4AI. 216 is exactly
 * the number of voxels the five letters need, so every piece is used.
 *
 * The poster image is the finished word. It is the whole hero whenever the
 * scene does not run: no JavaScript, no WebGL2, reduced motion or the site's
 * own "stop animations" setting. three.js is only downloaded when the scene
 * will actually play, and only after the page has loaded and gone idle.
 */
const stage = document.querySelector('.ai4ai-stage');
const holder = stage && stage.querySelector('.ai4ai-canvas');
const toggle = stage && stage.querySelector('.ai4ai-motion-toggle');
const intro = document.querySelector('.ai4ai-intro');
const root = document.documentElement;
const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const motionDisabled = () => reducedMotion.matches || root.classList.contains('a11y-no-motion');
const VENDOR = '../vendor/three-0.170.0/';

function webgl2Available() {
  try {
    return !!document.createElement('canvas').getContext('webgl2');
  } catch (error) {
    return false;
  }
}

let starting = false;
async function start() {
  if (starting || motionDisabled() || !webgl2Available()) return;
  starting = true;
  try {
    const [THREE, { RoundedBoxGeometry }, { RoomEnvironment }] = await Promise.all([
      import(VENDOR + 'three.module.min.js'),
      import(VENDOR + 'RoundedBoxGeometry.js'),
      import(VENDOR + 'RoomEnvironment.js'),
    ]);
    build(THREE, RoundedBoxGeometry, RoomEnvironment);
  } catch (error) {
    // The poster stays as the hero image.
  }
}

function build(THREE, RoundedBoxGeometry, RoomEnvironment) {
  const small = window.matchMedia('(max-width: 700px)');

  let renderer;
  try {
    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
  } catch (error) {
    return; // The poster stays as the hero image.
  }
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, small.matches ? 1.5 : 2));
  renderer.toneMapping = THREE.NeutralToneMapping;
  renderer.toneMappingExposure = 0.95;
  renderer.shadowMap.enabled = true;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  holder.appendChild(renderer.domElement);

  const scene = new THREE.Scene();
  const pmrem = new THREE.PMREMGenerator(renderer);
  scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
  pmrem.dispose();

  const camera = new THREE.PerspectiveCamera(30, 1, 0.1, 200);

  const key = new THREE.DirectionalLight(0xfff0dc, 2.6);
  key.position.set(-7, 11, 9);
  key.castShadow = true;
  const shadowSize = small.matches ? 1024 : 2048;
  key.shadow.mapSize.set(shadowSize, shadowSize);
  Object.assign(key.shadow.camera, { left: -11, right: 11, top: 9, bottom: -9, near: 1, far: 45 });
  key.shadow.bias = -0.0004;
  key.shadow.normalBias = 0.02;
  scene.add(key);
  const rim = new THREE.DirectionalLight(0xcfe0ff, 1.4);
  rim.position.set(9, 5, -9);
  scene.add(rim);

  const ground = new THREE.Mesh(new THREE.PlaneGeometry(120, 120), new THREE.ShadowMaterial({ opacity: 0.38 }));
  ground.rotation.x = -Math.PI / 2;
  ground.position.y = -1.77;
  ground.receiveShadow = true;
  scene.add(ground);

  // Nine glazes, one per learner group, ordered so the word reads as a spectrum.
  const GLAZES = ['#d9502f', '#e29a1f', '#b8875f', '#6f9a35', '#16857f', '#2f6fc4', '#1d3480', '#7a46a6', '#c23d78']
    .map((hex) => new THREE.Color(hex));
  const material = new THREE.MeshPhysicalMaterial({
    color: 0xffffff, roughness: 0.38, metalness: 0, clearcoat: 1, clearcoatRoughness: 0.12,
  });

  // ---- Cube ---------------------------------------------------------------
  const cubies = [];
  for (let y = 1; y >= -1; y--) for (let x = -1; x <= 1; x++) for (let z = -1; z <= 1; z++) {
    cubies.push({ home: new THREE.Vector3(x, y, z), glaze: (1 - y) * 3 + (x + 1) });
  }
  const cubeMesh = new THREE.InstancedMesh(new RoundedBoxGeometry(0.95, 0.95, 0.95, 4, 0.1), material, cubies.length);
  cubeMesh.castShadow = cubeMesh.receiveShadow = true;
  cubies.forEach((c, i) => cubeMesh.setColorAt(i, GLAZES[c.glaze]));
  scene.add(cubeMesh);

  const MOVES = [['y', 1, 1], ['x', -1, -1], ['z', 1, 1], ['x', 1, 1], ['y', -1, -1], ['z', 0, -1], ['y', 0, 1]];
  const AXES = { x: new THREE.Vector3(1, 0, 0), y: new THREE.Vector3(0, 1, 0), z: new THREE.Vector3(0, 0, 1) };
  const T = { intro: 0.9, scramble: 1.0, move: 0.42, gap: 0.06, explode: 4.55, split: 5.25, spread: 0.9, fly: 1.75 };
  T.form = T.split + T.spread + T.fly;
  T.rest = T.form + 12; // The scene settles and stops rendering after this.
  const CUBE_CENTRE = new THREE.Vector3(0, 0.75, 0);

  const ease = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
  const easeOut = (t) => 1 - Math.pow(1 - t, 3);
  const clamp01 = (t) => Math.min(1, Math.max(0, t));

  const tmpQ = new THREE.Quaternion();
  const IDENTITY = new THREE.Quaternion();
  const cubeQ = new THREE.Quaternion();
  const euler = new THREE.Euler();

  // Stateless: replays every completed layer turn, so any moment can be drawn.
  function cubieStates(t) {
    const states = cubies.map((c) => ({ p: c.home.clone(), q: new THREE.Quaternion() }));
    MOVES.forEach(([axis, layer, dir], k) => {
      const begin = T.scramble + k * (T.move + T.gap);
      if (t < begin) return;
      tmpQ.setFromAxisAngle(AXES[axis], dir * ease(clamp01((t - begin) / T.move)) * Math.PI / 2);
      for (const s of states) {
        if (Math.round(s.p[axis]) !== layer) continue;
        s.p.applyQuaternion(tmpQ);
        s.q.premultiply(tmpQ);
      }
    });
    const burst = 1 + 0.38 * ease(clamp01((t - T.explode) / (T.split - T.explode)));
    euler.set(0.48, -0.66 + Math.min(t, T.split) * 0.32, 0.06);
    cubeQ.setFromEuler(euler);
    const grow = easeOut(clamp01(t / T.intro));
    const scale = 0.35 + 0.65 * grow;
    return states.map((s) => ({
      p: s.p.multiplyScalar(burst * scale).applyQuaternion(cubeQ).add(CUBE_CENTRE).add(new THREE.Vector3(0, (1 - grow) * 0.8, 0)),
      q: s.q.premultiply(cubeQ),
      scale,
    }));
  }

  // ---- Letters: 5x7 voxel faces, three voxels deep (72 x 3 = 216) ----------
  const GLYPHS = {
    A: ['.###.', '#...#', '#...#', '#####', '#...#', '#...#', '#...#'],
    I: ['###', '.#.', '.#.', '.#.', '.#.', '.#.', '###'],
    4: ['...#.', '..##.', '.#.#.', '#..#.', '#####', '...#.', '...#.'],
  };
  const VOX = 0.5;
  const voxels = [];
  let col = 0;
  for (const ch of ['A', 'I', '4', 'A', 'I']) {
    GLYPHS[ch].forEach((row, r) => [...row].forEach((cell, c) => {
      if (cell !== '#') return;
      for (let d = 0; d < 3; d++) voxels.push({ col: col + c, row: r, d });
    }));
    col += GLYPHS[ch][0].length + 1;
  }
  const WORD_COLS = col - 1;
  voxels.forEach((v) => {
    v.target = new THREE.Vector3((v.col - (WORD_COLS - 1) / 2) * VOX, (3 - v.row) * VOX, (v.d - 1) * VOX);
  });
  voxels.sort((a, b) => a.col - b.col || a.row - b.row || a.d - b.d);

  // ---- Pieces: each cubie splits into eight -------------------------------------
  const OFFSETS = [];
  for (const x of [-1, 1]) for (const y of [-1, 1]) for (const z of [-1, 1]) OFFSETS.push(new THREE.Vector3(x, y, z).multiplyScalar(0.25));
  let seed = 7;
  const rand = () => (seed = (seed * 16807) % 2147483647) / 2147483647;

  const atSplit = cubieStates(T.split);
  const pieces = [];
  cubies.forEach((c, i) => OFFSETS.forEach((o) => pieces.push({
    glaze: c.glaze,
    start: o.clone().multiplyScalar(atSplit[i].scale).applyQuaternion(atSplit[i].q).add(atSplit[i].p),
    startQ: atSplit[i].q.clone(),
    spinAxis: new THREE.Vector3(rand() - 0.5, rand() - 0.5, rand() - 0.5).normalize(),
    spin: (1 + Math.floor(rand() * 2)) * Math.PI * (rand() < 0.5 ? -1 : 1),
    arc: new THREE.Vector3((rand() - 0.5) * 2, 1.2 + rand() * 2.2, 1 + rand() * 2.6),
    jitter: rand(),
    ripple: 0,
  })));
  // Glaze order maps onto left-to-right letter order.
  GLAZES.flatMap((_, g) => pieces.filter((p) => p.glaze === g)).forEach((p, i) => {
    p.voxel = voxels[i];
    p.target = voxels[i].target;
    p.delay = (p.voxel.col / WORD_COLS) * 0.62 + p.jitter * 0.28;
  });

  const pieceMesh = new THREE.InstancedMesh(new RoundedBoxGeometry(0.488, 0.488, 0.488, 3, 0.05), material, pieces.length);
  pieceMesh.castShadow = pieceMesh.receiveShadow = true;
  pieces.forEach((p, i) => pieceMesh.setColorAt(i, GLAZES[p.glaze]));
  scene.add(pieceMesh);

  // ---- Pointer: the word gives way under a mouse pointer ---------------------
  const pointer = { ndc: new THREE.Vector2(), x: 0, active: false };
  const raycaster = new THREE.Raycaster();
  const plane = new THREE.Plane(new THREE.Vector3(0, 0, 1), 0);
  const hit = new THREE.Vector3();

  // ---- Drawing -------------------------------------------------------------
  const m4 = new THREE.Matrix4();
  const v3 = new THREE.Vector3();
  const s3 = new THREE.Vector3();
  const q4 = new THREE.Quaternion();
  const ctrl = new THREE.Vector3();
  const lookAt = new THREE.Vector3();
  const halfFov = THREE.MathUtils.degToRad(camera.fov / 2);
  let rippling = false;

  function draw(t, dt) {
    const camBlend = ease(clamp01((t - 4.3) / 3.6));
    const formed = clamp01((t - T.form) / 1.2);
    const calm = 1 - ease(clamp01((t - (T.rest - 3)) / 3)); // idle motion fades out before rest
    const idle = Math.min(t, T.rest);

    const fit = (w, h) => Math.max(h, w / camera.aspect) / (2 * Math.tan(halfFov));
    const dist = THREE.MathUtils.lerp(fit(6.2, 5.8), fit(WORD_COLS * VOX + 2.4, 5.6), camBlend);
    const az = Math.sin(idle * 0.17) * THREE.MathUtils.lerp(0.42, 0.07, camBlend) * (camBlend < 1 ? 1 : calm) + pointer.x * 0.06;
    const el = THREE.MathUtils.lerp(0.22, 0.09, camBlend);
    lookAt.set(0, THREE.MathUtils.lerp(0.75, 0.05, camBlend), 0);
    camera.position.set(Math.sin(az) * Math.cos(el), Math.sin(el), Math.cos(az) * Math.cos(el)).multiplyScalar(dist).add(lookAt);
    camera.lookAt(lookAt);

    if (t < T.split) {
      cubeMesh.visible = true;
      pieceMesh.visible = false;
      cubieStates(t).forEach((s, i) => {
        cubeMesh.setMatrixAt(i, m4.compose(s.p, s.q, s3.setScalar(s.scale)));
      });
      cubeMesh.instanceMatrix.needsUpdate = true;
      return;
    }

    cubeMesh.visible = false;
    pieceMesh.visible = true;
    let hasHit = false;
    if (pointer.active && formed > 0.5) {
      raycaster.setFromCamera(pointer.ndc, camera);
      hasHit = !!raycaster.ray.intersectPlane(plane, hit);
    }
    rippling = false;
    pieces.forEach((p, i) => {
      const k = ease(clamp01((t - T.split - p.delay) / T.fly));
      ctrl.copy(p.start).lerp(p.target, 0.5).add(p.arc);
      v3.copy(p.start).multiplyScalar((1 - k) * (1 - k))
        .addScaledVector(ctrl, 2 * (1 - k) * k)
        .addScaledVector(p.target, k * k);
      q4.setFromAxisAngle(p.spinAxis, p.spin * (1 - k));
      q4.premultiply(tmpQ.copy(p.startQ).slerp(IDENTITY, k));
      let push = 0;
      if (hasHit) {
        const d2 = (p.target.x - hit.x) ** 2 + (p.target.y - hit.y) ** 2;
        push = 0.55 * Math.exp(-d2 / 0.9);
      }
      p.ripple += (push - p.ripple) * Math.min(1, dt * 7);
      if (Math.abs(p.ripple) > 0.002) rippling = true;
      v3.z += Math.sin(idle * 1.5 - p.voxel.col * 0.45) * 0.05 * formed * calm + p.ripple;
      pieceMesh.setMatrixAt(i, m4.compose(v3, q4, s3.setScalar(1)));
    });
    pieceMesh.instanceMatrix.needsUpdate = true;
  }

  // ---- Playback ------------------------------------------------------------
  let time = 0;
  let userPaused = false;
  let onScreen = true;
  let frame = 0;
  let last = 0;
  let shown = false;

  const running = () => !motionDisabled() && !userPaused && onScreen && document.visibilityState === 'visible';
  const rested = () => time >= T.rest;

  function render() {
    renderer.render(scene, camera);
    if (!shown) {
      shown = true;
      stage.classList.add('is-live');
    }
  }

  function loop(now) {
    frame = 0;
    const dt = last ? Math.min(0.05, (now - last) / 1000) : 0.016;
    last = now;
    if (running() && !rested()) time = Math.min(T.rest, time + dt);
    draw(time, dt);
    render();
    if ((running() && !rested()) || rippling) frame = requestAnimationFrame(loop);
    else updateButton();
  }

  function schedule() {
    if (!frame) {
      last = 0;
      frame = requestAnimationFrame(loop);
    }
  }

  function updateButton() {
    if (!toggle) return;
    toggle.hidden = motionDisabled();
    const action = rested() ? 'Replay' : userPaused ? 'Play' : 'Pause';
    toggle.textContent = action;
    toggle.setAttribute('aria-label', action + ' AI4AI animation');
  }

  function sync() {
    if (motionDisabled()) {
      time = T.rest; // finished word, no movement
      pointer.active = false;
    }
    updateButton();
    schedule();
  }

  if (toggle) {
    toggle.addEventListener('click', () => {
      if (rested()) {
        time = 0;
        userPaused = false;
      } else {
        userPaused = !userPaused;
      }
      sync();
    });
  }

  stage.addEventListener('pointermove', (event) => {
    if (event.pointerType !== 'mouse' || motionDisabled() || userPaused) return;
    const box = stage.getBoundingClientRect();
    pointer.ndc.set(((event.clientX - box.left) / box.width) * 2 - 1, -((event.clientY - box.top) / box.height) * 2 + 1);
    pointer.x = rested() ? 0 : pointer.ndc.x;
    pointer.active = true;
    schedule();
  });
  stage.addEventListener('pointerleave', () => {
    pointer.active = false;
    pointer.x = 0;
    schedule();
  });

  function applyTheme() {
    const light = intro && intro.classList.contains('is-light');
    ground.material.opacity = light ? 0.16 : 0.38;
    scene.environmentIntensity = light ? 0.75 : 0.55;
    schedule();
  }

  function resize() {
    const { width, height } = holder.getBoundingClientRect();
    if (!width || !height) return;
    renderer.setSize(width, height, false);
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    schedule();
  }

  new ResizeObserver(resize).observe(holder);
  new IntersectionObserver((entries) => {
    onScreen = entries[entries.length - 1].isIntersecting;
    sync();
  }).observe(stage);
  document.addEventListener('visibilitychange', sync);
  reducedMotion.addEventListener('change', sync);
  new MutationObserver(sync).observe(root, { attributeFilter: ['class'] });
  if (intro) new MutationObserver(applyTheme).observe(intro, { attributeFilter: ['class'] });

  // Compile both meshes' shaders up front, off the main thread where the GPU
  // allows it, so the burst does not stall when the pieces first appear.
  const begin = () => {
    applyTheme();
    resize();
    sync();
  };
  if (renderer.extensions.has('KHR_parallel_shader_compile')) {
    renderer.compileAsync(scene, camera).then(begin, begin);
  } else {
    renderer.compile(scene, camera);
    begin();
  }
}

// Start once the page has loaded and the browser is idle: the poster covers
// the wait, and set-up work stays clear of the first interactions.
function startWhenIdle() {
  const go = () => ('requestIdleCallback' in window ? requestIdleCallback(start, { timeout: 1500 }) : setTimeout(start, 200));
  if (document.readyState === 'complete') go();
  else window.addEventListener('load', go, { once: true });
}

if (stage && holder) {
  startWhenIdle();
  // Motion switched back on later (system setting or the site's panel).
  const retry = () => { if (!motionDisabled()) startWhenIdle(); };
  reducedMotion.addEventListener('change', retry);
  new MutationObserver(retry).observe(root, { attributeFilter: ['class'] });
}
