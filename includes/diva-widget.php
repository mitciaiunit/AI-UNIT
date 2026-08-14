<?php
/**
 * DIVA chat assistant.
 *
 * $divaAvailable is false when DIVA_API_URL is unset or unusable (see
 * diva_api_url() in app/Helpers/functions.php). In that state the assistant is
 * still shown - dimmed, disabled and labelled - rather than removed, so an
 * outage reads as an outage instead of as a feature that was never built.
 *
 * The panel markup is rendered either way. It cannot be opened while the
 * trigger is disabled, and keeping it means every element assets/js/script.js
 * looks up on load still resolves; removing it would leave that script holding
 * a handful of nulls.
 */
$divaAvailable = diva_api_url() !== '';
?>
<!-- DIVA CHATBOT -->
<div class="diva-widget<?= $divaAvailable ? '' : ' diva-widget--unavailable' ?>" aria-label="DIVA Chat Assistant" id="divaWidget">
  <?php
  /*
   * aria-labelledby rather than aria-label: the accessible name is taken from
   * the heading the user can actually see, so the two cannot drift apart, and
   * it stays correct if the heading is ever translated.
   *
   * aria-modal is NOT set here. It is added by assets/js/script.js only while
   * the panel is open and its focus trap is active - the same discipline the
   * accessibility panel already follows. Left on permanently, screen readers
   * that enforce it strictly (NVDA) treat the panel as an always-active modal
   * and hide the rest of the page from first load.
   */
  ?>
  <div class="diva-panel" id="divaPanel" role="dialog" aria-labelledby="divaPanelTitle">
    <div class="diva-panel-header">
      <img src="<?= e(asset('images/DIVA.png')) ?>" alt="DIVA">
      <div class="diva-panel-header-text">
        <h4 id="divaPanelTitle">DIVA</h4>
        <p>Digital Interactive Virtual Assistant</p>
        <div class="diva-online"><span class="diva-online-dot"></span><span data-i18n="diva_online">Online &amp; ready to help</span></div>
      </div>
      <button class="diva-close" id="divaClose" aria-label="Close DIVA chat">✕</button>
      <button class="diva-clear" id="divaClear" aria-label="Clear conversation">Clear</button>
    </div>
    <div class="diva-messages" id="divaMessages" role="log" aria-live="polite" aria-atomic="false" aria-relevant="additions" tabindex="0" aria-label="Conversation with DIVA">
      <div class="diva-msg bot"><span class="visually-hidden" data-i18n="diva_speaker_bot">DIVA said:</span><span data-i18n="diva_welcome">Hello! I'm <strong>DIVA</strong> - the Government of Mauritius' AI assistant. I'm here to help you with questions about our Digital Transformation Blueprint, AI strategy, and government services.<br><br>You can also <strong>speak to me</strong> - press the microphone button below and ask your question out loud.</span></div>
      <div class="diva-suggestions">
        <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug1">What is the Digital Transformation Blueprint?</button>
        <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug2">What does FAIR stand for in the AI Framework?</button>
        <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug3">How is AI used in Mauritius government services?</button>
      </div>
    </div>
    <!--
      Visually hidden, separate from #divaMessages so transient status text
      ("DIVA is typing…") is announced without becoming part of the permanent
      transcript. role="status" is its own live region - independent of
      aria-atomic="false" above, so this always reads its full current
      content. Cleared as soon as the reply lands, so it goes silent again
      instead of restating stale status the next time it changes.
    -->
    <div id="divaStatus" role="status" aria-live="polite" class="visually-hidden"></div>

<!-- Buttons -->
    <div class="diva-input-row">
      <input
        type="text"
        id="divaInput"
        placeholder="Type or speak your question…"
        aria-label="Type your message to DIVA"
      />
      <button class="diva-mic" id="divaMic" aria-label="Voice Input">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 1a4 4 0 0 1 4 4v6a4 4 0 0 1-8 0V5a4 4 0 0 1 4-4z"/>
    <path d="M19 10a7 7 0 0 1-14 0"/>
    <line x1="12" y1="19" x2="12" y2="23"/>
    <line x1="8" y1="23" x2="16" y2="23"/>
  </svg>
</button>
      <button class="diva-send" id="divaSend" aria-label="Send message">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2.5">
          <line x1="22" y1="2" x2="11" y2="13"/>
          <polygon points="22 2 15 22 11 13 2 9 22 2"/>
        </svg>
      </button>
    </div>

    <div class="diva-footer">Powered by <span>AI Unit · Ministry of ICT, Mauritius</span></div>
  </div>
  <?php if (!$divaAvailable): ?>
    <?php
    /*
     * Rendered by the server, not revealed by script.js, for two reasons: it
     * is correct before any JavaScript runs (no moment where a dead assistant
     * looks ready), and it is still correct if the script never loads at all.
     *
     * The message has to be visible without interaction, because the trigger
     * beneath it is genuinely disabled - a disabled button cannot be clicked
     * or focused, so it can never be the thing that explains itself.
     * aria-describedby ties the two together for anyone reading the button.
     */
    ?>
    <p class="diva-unavailable" id="divaUnavailable">The DIVA assistant is temporarily unavailable.</p>
  <?php endif; ?>

  <button class="diva-trigger" id="divaTrigger" aria-label="Open DIVA chat assistant" aria-expanded="false"
    <?= $divaAvailable ? '' : ' disabled aria-describedby="divaUnavailable"' ?>>
    <img src="<?= e(asset('images/DIVA.png')) ?>" alt="Chat with DIVA">
  </button>
</div>
