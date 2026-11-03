# CLAUDE.md — NuisiVox

Site statique en HTML (pas de générateur) de **NuisiVox** : dératisation et désinsectisation dans les
Bouches-du-Rhône (entreprise de Mathias Sergent). Propriétaire et administrateur : Pascal Sergent.
Répondre en **français**, simplement.

## Mise en ligne
- **Par GitHub** : chaque `git push` sur `main` est mis en ligne. Le propriétaire veut que chaque
  modification demandée soit mise en ligne (commit en français, puis push sur `main`).
- Avant de pousser : relire le HTML modifié, vérifier les liens internes, mettre à jour `sitemap.xml`
  si une page est créée ou supprimée.
- Ce dossier `.claude/` et les fichiers `.md` ne doivent pas être servis (règle dans `.htaccess`).

## Positionnement (décisions du propriétaire)
- Nuisibles principaux : **rats, souris, blattes/cafards, fourmis, guêpes, frelons**.
- **Punaises de lit : pas traitées sur NuisiVox**, renvoyées vers Punaises Assistance (décision du 2026-11-02).
- **Villes principales : Marseille, Aix-en-Provence, Aubagne** (décision du 2026-11-03).
  **Trets n'est plus une ville principale** : page conservée, au même rang que Gardanne et Gémenos
  (pas dans le menu principal ni dans les titres). Villes voisines citées naturellement dans les pages.

## Pages locales « prestation + ville » (stratégie validée le 2026-11-03)
- Une page = une intention : une prestation dans une ville (ex. « Dératisation à Marseille »).
- Chaque page doit être **différente à plus de 70 %** des autres : terrain local réel (quartiers,
  bâti, situations typiques), villes voisines, exemple d'intervention propre à la page, FAQ propre.
  Jamais un gabarit dupliqué en changeant le nom de la ville.
- Pages villes (`marseille.html`, `aix-en-provence.html`, `aubagne.html`) = pages d'accueil par ville,
  qui renvoient vers leurs pages « prestation + ville ». Pages services (`deratisation.html`…) = guides
  régionaux (méthodes, protocoles, limites) : leur title ne vise plus une ville précise.
- Vagues : 1) Dératisation Marseille, Aix, Aubagne + Cafards Marseille, Aix ; 2) avant le printemps :
  Guêpes et frelons Aubagne, Aix + Fourmis Marseille ; 3) étendre seulement ce qui progresse dans la
  Search Console.

## Exemples d'intervention et chantiers réels (consigne du 2026-11-03)
- **En attendant les chantiers réels** : des **exemples d'intervention** illustrés par des **photos libres
  de droit** (licence vérifiée, source et auteur notés), avec le badge visible « Exemple d'intervention ».
  **Chaque exemple est unique** : jamais le même exemple ni la même photo sur deux pages.
- **Après chaque chantier**, le propriétaire envoie quelques photos et du contexte : intégrer
  l'intervention réelle sur la ou les pages concernées, **optimisée SEO et GEO** (lieu, nuisible, méthode,
  chiffres, résultat, texte alternatif descriptif), en remplacement d'un exemple. Ne jamais inventer un fait
  et ne jamais présenter un exemple comme un chantier réel.
- Clients : jamais de nom ni d'adresse (commune ou arrondissement seulement). Logos de fournisseurs floutés.

## Règles générales
- Raisonner comme un expert SEO (intention de recherche, cocon sémantique, maillage contextuel), rester
  naturel, sans bourrage de mots-clés ni promesse invérifiable.
- Aucun avis, note ou témoignage inventé ; aucune donnée structurée pour ce qui n'est pas visible.
- Aucun secret dans le dépôt (le dépôt est public).
