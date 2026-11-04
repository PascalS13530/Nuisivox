// Contrôle du site construit (dist/) avant mise en ligne : lancé par `npm run check`.
// Bloque si : lien interne cassé, page du plan du site absente, H1 absent ou multiple,
// title / description / canonical manquants, title ou description en double, image sans alt.
import { readFileSync, readdirSync, existsSync } from 'node:fs';
import { join } from 'node:path';

const DIST = 'dist';
const erreurs = [];
const alertes = [];
const pages = readdirSync(DIST).filter((f) => f.endsWith('.html'));
const titres = new Map();
const descriptions = new Map();
const decode = (s) => s.replace(/&#x27;|&#39;/g, "'").replace(/&quot;/g, '"').replace(/&amp;/g, '&');

for (const nom of pages) {
  const html = readFileSync(join(DIST, nom), 'utf8');
  const titre = decode((html.match(/<title>([^<]*)<\/title>/) || [])[1] || '');
  const desc = decode((html.match(/<meta name="description" content="([^"]*)"/) || [])[1] || '');
  if (!titre) erreurs.push(`${nom} : title manquant`);
  else if (titre.length > 65) alertes.push(`${nom} : title de ${titre.length} caractères`);
  if (!desc) erreurs.push(`${nom} : meta description manquante`);
  else if (desc.length > 160) alertes.push(`${nom} : description de ${desc.length} caractères`);
  if (titre && titres.has(titre)) erreurs.push(`${nom} : title identique à ${titres.get(titre)}`); else titres.set(titre, nom);
  if (desc && descriptions.has(desc)) erreurs.push(`${nom} : description identique à ${descriptions.get(desc)}`); else descriptions.set(desc, nom);
  if (!/<link rel="canonical" href="https:\/\/nuisivox\.fr\//.test(html)) erreurs.push(`${nom} : canonical absente`);
  const h1 = (html.match(/<h1[\s>]/g) || []).length;
  if (h1 !== 1) erreurs.push(`${nom} : ${h1} H1 (1 attendu)`);
  for (const img of html.match(/<img\b[^>]*>/g) || []) {
    if (!/\balt="[^"]+"/.test(img)) erreurs.push(`${nom} : image sans texte alternatif ${img.slice(0, 80)}`);
  }
  for (const [, href] of html.matchAll(/<a\b[^>]*href="([^"]+)"/g)) {
    if (/^(https?:|mailto:|tel:|#)/.test(href)) continue;
    const fichier = href.split('#')[0].split('?')[0];
    if (fichier && !existsSync(join(DIST, fichier))) erreurs.push(`${nom} : lien cassé vers ${href}`);
  }
  for (const [, json] of html.matchAll(/<script type="application\/ld\+json">([\s\S]*?)<\/script>/g)) {
    try { JSON.parse(json); } catch { erreurs.push(`${nom} : données structurées (JSON-LD) invalides`); }
  }
}

const plan = readFileSync(join(DIST, 'sitemap.xml'), 'utf8');
const dansPlan = new Set();
for (const [, url] of plan.matchAll(/<loc>https:\/\/nuisivox\.fr\/([^<]*)<\/loc>/g)) {
  dansPlan.add(url || 'index.html');
  if (!existsSync(join(DIST, url || 'index.html'))) erreurs.push(`sitemap.xml : page absente ${url}`);
}
for (const nom of pages) {
  const html = readFileSync(join(DIST, nom), 'utf8');
  if (!dansPlan.has(nom) && !/name="robots" content="noindex/.test(html)) alertes.push(`${nom} : absente de sitemap.xml`);
}

console.log(`=== Vérification du site : ${pages.length} pages ===`);
for (const a of alertes) console.log('⚠️ ', a);
for (const e of erreurs) console.log('❌', e);
if (erreurs.length) { console.log(`${erreurs.length} erreur(s) : corriger avant de mettre en ligne.`); process.exit(1); }
console.log('✅ Aucune erreur.');
