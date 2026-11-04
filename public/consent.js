/* Mesure d'audience Google Analytics 4 : chargée uniquement après accord du visiteur (RGPD / CNIL).
   Choix mémorisé 6 mois dans ce navigateur. Lien « Gérer les cookies » dans le pied de page. */
(function () {
  var GA_ID = "G-97F18EQTDD";
  var KEY = "nuisivox-consent";
  var SIX_MOIS = 1000 * 60 * 60 * 24 * 182;

  function lire() {
    try {
      var v = JSON.parse(localStorage.getItem(KEY) || "null");
      if (v && Date.now() - v.t < SIX_MOIS) return v.c;
    } catch (e) {}
    return null;
  }
  function ecrire(c) {
    try { localStorage.setItem(KEY, JSON.stringify({ c: c, t: Date.now() })); } catch (e) {}
  }

  var charge = false;
  function chargerGA() {
    if (charge) return; charge = true;
    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { dataLayer.push(arguments); };
    gtag("js", new Date());
    gtag("config", GA_ID, { anonymize_ip: true });
    var s = document.createElement("script");
    s.async = true;
    s.src = "https://www.googletagmanager.com/gtag/js?id=" + GA_ID;
    document.head.appendChild(s);
  }
  function effacerCookiesGA() {
    document.cookie.split(";").forEach(function (c) {
      var n = c.split("=")[0].trim();
      if (n.indexOf("_ga") === 0) {
        var d = location.hostname.replace(/^www\./, "");
        ["", "; domain=" + d, "; domain=." + d].forEach(function (dom) {
          document.cookie = n + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/" + dom;
        });
      }
    });
  }

  var bandeau;
  function fermer() { if (bandeau) { bandeau.remove(); bandeau = null; } }
  function afficher() {
    if (bandeau) return;
    bandeau = document.createElement("div");
    bandeau.className = "consent";
    bandeau.setAttribute("role", "dialog");
    bandeau.setAttribute("aria-label", "Cookies de mesure d'audience");
    bandeau.innerHTML =
      '<p>Avec votre accord, nous mesurons la fréquentation du site (Google Analytics) pour l\'améliorer. ' +
      'Aucune publicité. <a href="confidentialite.html#cookies">En savoir plus</a></p>' +
      '<div class="consent-actions">' +
      '<button type="button" class="btn btn-ghost" data-c="non">Refuser</button>' +
      '<button type="button" class="btn btn-primary" data-c="oui">Accepter</button></div>';
    bandeau.addEventListener("click", function (e) {
      var c = e.target.getAttribute && e.target.getAttribute("data-c");
      if (!c) return;
      ecrire(c);
      fermer();
      if (c === "oui") chargerGA(); else effacerCookiesGA();
    });
    document.body.appendChild(bandeau);
  }

  window.nuisivoxCookies = function () { afficher(); };

  function init() {
    var c = lire();
    if (c === "oui") chargerGA();
    else if (c === null) afficher();
    document.querySelectorAll("[data-cookies]").forEach(function (a) {
      a.addEventListener("click", function (e) { e.preventDefault(); afficher(); });
    });
  }
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
  else init();
})();
