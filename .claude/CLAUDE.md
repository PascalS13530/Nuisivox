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
- Communes voisines par bassin de vie (2026-11-03) : **Trets est citée sur toutes les pages d'Aix**
  (page ville et pages « prestation + Aix »), avec Rousset, Peynier, Fuveau, Châteauneuf-le-Rouge, Meyreuil ;
  Auriol, La Bouilladisse, La Destrousse, Belcodène, Roquevaire sur les pages d'Aubagne ;
  Pourrières, Puyloubier, Saint-Maximin sur la page Trets ; Gréasque sur Gardanne. Toujours dans des phrases utiles, jamais en liste.

## Pages locales « prestation + ville » (stratégie validée le 2026-11-03)
- Une page = une intention : une prestation dans une ville (ex. « Dératisation à Marseille »).
- Chaque page doit être **différente à plus de 70 %** des autres : terrain local réel (quartiers,
  bâti, situations typiques), villes voisines, exemple d'intervention propre à la page, FAQ propre.
  Jamais un gabarit dupliqué en changeant le nom de la ville.
- Pages villes (`marseille.html`, `aix-en-provence.html`, `aubagne.html`) = pages d'accueil par ville,
  qui renvoient vers leurs pages « prestation + ville ». Pages services (`deratisation.html`…) = guides
  régionaux (méthodes, protocoles, limites) : leur title ne vise plus une ville précise.
- Vagues : 1) Dératisation Marseille, Aix, Aubagne + Cafards Marseille, Aix, Aubagne ; 2) avant le printemps :
  Guêpes et frelons Aubagne, Aix + Fourmis Marseille ; 3) étendre seulement ce qui progresse dans la
  Search Console.

## Exemples d'intervention et chantiers réels (consigne du 2026-11-03)
- **En attendant les chantiers réels** : des **exemples d'intervention** illustrés par des **photos libres
  de droit** (licence vérifiée, source et auteur notés), avec le badge visible « Exemple d'intervention ».
  **Chaque exemple est unique** : jamais le même exemple ni la même photo sur deux pages.
  Seule exception : le carnet de tournée (`carnet.html`), qui rassemble tous les exemples. Les pages sans exemple propre
  (accueil, guides, technicien) renvoient vers le carnet. Pages villes et pages nuisibles visées : 1 200 à 1 500 mots.
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

## RankFlow (journal)
- 2026-11-03 : Dératisation Marseille 93/100, Aix 93/100, Aubagne 93/100 ; GEO 10/10 (16/16 signaux) sur les trois.
  Plan d'actions RankFlow : maximum atteignable 93. Écarts assumés : « intention » jugée informationnelle
  (contenu explicatif riche, conservé : c'est lui qui différencie les pages) ; « Organization.sameAs » manquant
  tant que NuisiVox n'a pas de fiche Google ni de profils officiels (ne jamais inventer de profil).

## Tarifs à utiliser dans les exemples et les FAQ (consigne du 2026-11-03)
S'inspirer de la grille 2026 fournie par le propriétaire (tarifs indicatifs, HT) :
- Fourmis, cafards, mouches, moucherons : traitement chimique intérieur 163 € HT le passage (extérieur sur devis).
- Dératisation (mécanique ou rodenticide) : 150 € HT le passage, majoration sur devis dans les combles ou vides sanitaires.
- Guêpes, frelons : nid à moins de 5 m, visible et accessible, 90 € HT ; au-delà sur devis.
- Autres nuisibles et contrats annuels de prévention : sur devis.
- Devis gratuit ; pas de frais de déplacement dans le 13 et le 83 ; majoration jusqu'à 50 % week-ends, fériés et nuits (19 h - 7 h).
Toujours préciser « à titre indicatif » et « HT ». Punaises de lit : non traitées sur NuisiVox.
- Nombre de passages (consigne du 2026-11-03) : dératisation et désinsectisation = 1 ou 2 passages ; un 3e est rare,
  et s'il est nécessaire il est fait au titre du SAV, gratuitement. Ne jamais écrire « 3 passages » dans un exemple facturé.
- 2026-11-03 : Cafards Marseille 93/100, Aix 94/100, Aubagne 93/100 ; GEO 10/10 ; intention détectée « transactionnelle »
  (tarifs indicatifs dans les FAQ). Maximum atteignable selon RankFlow : 93-94 (sameAs manquant, voir plus haut).
