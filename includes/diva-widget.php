<!-- DIVA CHATBOT -->
<div class="diva-widget" aria-label="DIVA Chat Assistant" id="divaWidget">
  <div class="diva-panel" id="divaPanel" role="dialog" aria-label="Chat with DIVA">
    <div class="diva-panel-header">
      <img src="<?= e(asset('images/DIVA.png')) ?>" alt="DIVA">
      <div class="diva-panel-header-text">
        <h4>DIVA</h4>
        <p>Digital Interactive Virtual Assistant</p>
        <div class="diva-online"><span class="diva-online-dot"></span><span data-i18n="diva_online">Online &amp; ready to help</span></div>
      </div>
      <button class="diva-close" id="divaClose" aria-label="Close DIVA chat">✕</button>
      <button class="diva-clear" id="divaClear" aria-label="Clear conversation">Clear</button>
    </div>
    <div class="diva-messages" id="divaMessages" aria-live="polite">
      <div class="diva-msg bot" data-i18n="diva_welcome">Hello! I'm <strong>DIVA</strong> - the Government of Mauritius' AI assistant. I'm here to help you with questions about our Digital Transformation Blueprint, AI strategy, and government services.<br><br>You can also <strong>speak to me</strong> - press the microphone button below and ask your question out loud.</div>
      <div class="diva-suggestions">
        <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug1">What is the Digital Transformation Blueprint?</button>
        <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug2">What does FAIR stand for in the AI Framework?</button>
        <button class="diva-suggestion-btn" onclick="pickSuggestion(this)" data-i18n="diva_sug3">How is AI used in Mauritius government services?</button>
      </div>
    </div>

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
  <button class="diva-trigger" id="divaTrigger" aria-label="Open DIVA chat assistant" aria-expanded="false">
    <img src="<?= e(asset('images/DIVA.png')) ?>" alt="Chat with DIVA">
  </button>
</div>
