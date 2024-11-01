function getCookie(n) {
  return document.cookie
    .split('; ')
    .find((row) => row.startsWith(n))
    ?.split('=')[1];
}

function createCookie(name, value, days) {
  let expires = '';
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = '; expires=' + date.toUTCString();
  }
  document.cookie = name + '=' + (value || '') + expires + '; path=/';
}

function getUrlParam(urlParam) {
  return new URLSearchParams(window.location.search).get(urlParam);
}

function getRandomUUID() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    const r = Math.random() * 16 | 0;
    const v = c === 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

(function () {
  // Check if the browser data is already in a cookie
  const cookie = getCookie('_tb_b');
  if (!cookie) {
    // If it isn't, store it
    const data = {
      clientId: typeof crypto.randomUUID === "function" ? crypto.randomUUID() : getRandomUUID(),
      context: {
        document: {
          location: {
            href: document.location.href,
            hash: document.location.hash,
            host: document.location.host,
            hostname: document.location.hostname,
            origin: document.location.origin,
            pathname: document.location.pathname,
            port: document.location.port,
            protocol: document.location.protocol,
            search: document.location.search,
          },
          title: document.title,
          referrer: document.referrer,
          characterSet: document.characterSet,
        },
        navigator: {
          language: navigator.language,
          cookieEnabled: navigator.cookieEnabled,
          languages: navigator.languages,
          userAgent: navigator.userAgent,
        },
        window: {
          innerHeight: window.innerHeight,
          innerWidth: window.innerWidth,
          outerHeight: window.outerHeight,
          outerWidth: window.outerWidth,
          pageXOffset: window.pageXOffset,
          pageYOffset: window.pageYOffset,
          screenX: window.screenX,
          screenY: window.screenY,
          scrollX: window.scrollX,
          scrollY: window.scrollY,
        },
      },
    };
    const base64Data = btoa(encodeURIComponent(JSON.stringify(data)));
    createCookie('_tb_b', base64Data, 400);
  } else {
    // If it is, update the data
    const data = JSON.parse(decodeURIComponent(atob(cookie)));

    // Update the document
    data.context.document = {
      title: document.title,
      referrer: document.referrer,
      characterSet: document.characterSet,
      location: {
        href: document.location.href,
        hash: document.location.hash,
        host: document.location.host,
        hostname: document.location.hostname,
        origin: document.location.origin,
        pathname: document.location.pathname,
        port: document.location.port,
        protocol: document.location.protocol,
        search: document.location.search,
      }
    };

    // Update the navigator
    data.context.navigator = {
      language: navigator.language,
      cookieEnabled: navigator.cookieEnabled,
      languages: navigator.languages,
      userAgent: navigator.userAgent,
    };

    // Update the window
    data.context.window = {
      innerHeight: window.innerHeight,
      innerWidth: window.innerWidth,
      outerHeight: window.outerHeight,
      outerWidth: window.outerWidth,
      pageXOffset: window.pageXOffset,
      pageYOffset: window.pageYOffset,
      screenX: window.screenX,
      screenY: window.screenY,
      scrollX: window.scrollX,
      scrollY: window.scrollY,
    };

    // Update the cookie
    const base64Data = btoa(encodeURIComponent(JSON.stringify(data)));
    createCookie('_tb_b', base64Data, 400);
  }

  // Get the marketing attributes from the url parameters
  const params = [
    // Key is the name of the key in the order notes, cookie is the
    // name of the cookie and value is the value retrieved from the URL.
    {key: "epik", cookie: "_epik", value: getUrlParam("epik")},
    {key: "fbc", cookie: "_fbc", value: getUrlParam("fbclid")},
    {key: "ttclid", cookie: "_ttclid", value: getUrlParam("ttclid")},
    {key: "gclid", cookie: "_gclid", value: getUrlParam("gclid")},
    {key: "gbraid", cookie: "_gbraid", value: getUrlParam("gbraid")},
    {key: "wbraid", cookie: "_wbraid", value: getUrlParam("wbraid")},
    {key: "kx", cookie: "_kx", value: getUrlParam("_kx")},
    {key: "utmCampaign", cookie: "_utm_campaign", value: getUrlParam("utm_campaign") || getUrlParam("tw_campaign")},
    {key: "utmAdGroup", cookie: "_utm_ad_group", value: getUrlParam("utm_ad_group")},
    {key: "utmAd", cookie: "_utm_ad", value: getUrlParam("utm_ad") || getUrlParam("tw_adid")},
    {key: "utmSource", cookie: "_utm_source", value: getUrlParam("utm_source")},
  ];

  // Check if there are any values in the url parameters
  const values = params.filter((v) => v.value != null);

  // Create cookies for the marketing attributes
  params.forEach((param) => {
    // If there are new values, create cookie with old prefix
    if (values.length > 0) {
      const oldCookie = getCookie(param.cookie);
      const oldCookieKey = "_old" + param.cookie;
      if (oldCookie) createCookie(oldCookieKey, oldCookie, 400);
      // Remove old cookie
      document.cookie = param.cookie
          + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    // If new value, create new cookie
    if (param.value) {
      let cookieValue;
      // Fbc value has a specific format
      if (param.key === "fbc") {
        cookieValue = param.value ? "fb.1." + Date.now() + "." + param.value : null;
      } else {
        cookieValue = param.value;
      }
      createCookie(param.cookie, cookieValue, 400);
    }
  });

  // If there's no fbp value yet, generate one and store it in a cookie
  const fbp = getCookie('_fbp');
  if (!fbp) {
    const randomNumber = Math.floor(
      1000000000 + Math.random() * 9000000000
    );
    const fbp = 'fb.1.' + Date.now() + '.' + randomNumber;
    createCookie('_fbp', fbp, 400);
  }

  // If there's no ttp value yet, generate one and store it in a cookie
  const ttp = getCookie('_ttp');
  if (!ttp) {
    const ttp = typeof crypto.randomUUID === "function"
      ? crypto.randomUUID() + crypto.randomUUID()
      : getRandomUUID() + getRandomUUID();
    createCookie('_ttp', ttp, 400);
  }

  let savedUUID = localStorage.getItem("tbId");
  if (!savedUUID) {
    if (typeof crypto.randomUUID !== "function") {
      savedUUID = getRandomUUID();
    } else {
      const uuid = crypto.randomUUID();
      localStorage.setItem("tbId", uuid);
      savedUUID = uuid;
    }
  }

  const tbId = getCookie("_tb_id");
  if (!tbId) {
    createCookie("_tb_id", savedUUID, 400);
  }

  const landingSiteUrl = getCookie("_tb_l");
  if (!landingSiteUrl) {
    createCookie("_tb_l", document.location.href, 400);
  }

  const referringSiteUrl = getCookie("_tb_r");
  if (!referringSiteUrl) {
    createCookie("_tb_r", document.referrer, 400);
  }

  const currentPath = window.location.pathname;
  let productSlug;
  if (currentPath.includes('/product/')) {
    // If the current page is a product page, get the product slug
    productSlug = currentPath.replace(/\/+$/, '').split('/').pop();
    // Send the product slug to the server and trigger the page loaded action
  }
  jQuery.post({
  url: trackBee.ajaxurl,
    data: {
      action: 'trackbee_page_loaded',
      productSlug: productSlug || null,
    },
  });
})();
