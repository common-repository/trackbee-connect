window.addEventListener('load', function () {
  const apiKeyInput = document.getElementById('trackbee_api_key');

  if (apiKeyInput) {
    // If the user has already saved an API key, set the color of the indicator to yellow
    if (document.getElementById('trackbee_api_key').value.length > 0) {
      const indicator = document.querySelectorAll('#trackbeeIndicator');
      for (let i = 0; i < indicator.length; i++) {
        indicator[i].attributes['fill'].nodeValue = "#FEC119";
      }
    }
    // Set color of indicator based on input value
    const indicator = document.querySelectorAll('#trackbeeIndicator');
    const apiKeyInput = document.getElementById('trackbee_api_key');
    apiKeyInput.addEventListener('input', function (e) {
      for (let i = 0; i < indicator.length; i++) {
        indicator[i].attributes['fill'].nodeValue = e.target.value.length > 0 ? "#FEC119" : "#A7A7A7";
      }
    });
  }
});