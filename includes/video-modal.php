<!-- VIDEO MODAL -->
<div id="videoModal" class="video-modal">
  <div class="modal-content">
    <button class="close-modal" id="closeModalBtn">&times;</button>

    <video
      id="modalVideo"
      controls
      preload="metadata"
      tabindex="0"
    >
      <source src="" type="video/mp4">

      <!-- English -->
      <track
        id="track-en"
        kind="subtitles"
        srclang="en"
        label="English"
      >

      <!-- French -->
      <track
        id="track-fr"
        kind="subtitles"
        srclang="fr"
        label="Français"
      >

      <!-- Kreol -->
      <track
        id="track-km"
        kind="subtitles"
        srclang="mfe"
        label="Kreol Morisien"
        default
      >
      Your browser does not support the video tag.
    </video>

    <div id="modalVideoTitle" class="video-title-modal"></div>
  </div>
</div>
