-- *********
-- Requete 1
-- *********

SELECT titre, ROUND(AVG(note), 1) AS top_note
FROM salle, avis
WHERE salle.id_salle = avis.id_salle
GROUP BY salle.id_salle
ORDER BY top_note DESC
LIMIT 0, 3;

-- *********
-- Requete 2 (salle les plus commandées)
-- *********

SELECT titre, COUNT(id_commande) AS top_commande
FROM salle, produit, commande
WHERE salle.id_salle = produit.id_salle
AND produit.id_produit = commande.id_produit
GROUP BY salle.id_salle
ORDER BY top_commande DESC
LIMIT 0, 3;

-- *********
-- Requete 3 (membres qui achètent le plus)
-- *********

SELECT pseudo, COUNT(id_commande) AS top_achat
FROM membre, commande
WHERE membre.id_membre = commande.id_membre
GROUP BY membre.id_membre
ORDER BY top_achat DESC
LIMIT 0, 3;

-- *********
-- Requete 4 (membres qui dépensent le plus)
-- *********

SELECT pseudo, SUM(prix) AS top_depense
FROM membre, commande, produit
WHERE membre.id_membre = commande.id_membre
AND commande.id_produit = produit.id_produit
GROUP BY membre.id_membre
ORDER BY top_depense DESC
LIMIT 0, 3;