PRAGMA foreign_keys = ON;

CREATE TABLE operateur
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(250) NOT NULL
);

CREATE TABLE prefixe
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe INTEGER NOT NULL UNIQUE,
    operateur INTEGER NOT NULL,
    FOREIGN KEY (operateur) REFERENCES operateur(id)
);

CREATE TABLE frais_autre_operateur
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur INTEGER NOT NULL,
    frais DECIMAL(10,2),
    FOREIGN KEY (operateur) REFERENCES operateur(id)
);


CREATE TABLE type_operation
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);


CREATE TABLE bareme
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    valeur_min INTEGER NOT NULL,
    valeur_max INTEGER NOT NULL,
    frais INTEGER NOT NULL,
    type_operation_id INTEGER NOT NULL,
    FOREIGN KEY(type_operation_id) REFERENCES type_operation(id)
);


CREATE TABLE clients
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    numero INTEGER NOT NULL UNIQUE,
    date_creation TEXT NOT NULL
);


CREATE TABLE transactions
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    type_operation_id INTEGER NOT NULL,
    valeur INTEGER NOT NULL,
    frais INTEGER NOT NULL DEFAULT 0,
    date_transaction TEXT NOT NULL,
    operateur_id INTEGER NOT NULL DEFAULT 1,

    FOREIGN KEY(client_id) REFERENCES clients(id),
    FOREIGN KEY(type_operation_id) REFERENCES type_operation(id),
    FOREIGN KEY(operateur_id) REFERENCES operateur(id)
);


CREATE TABLE transfert
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_source INTEGER NOT NULL,
    client_destination INTEGER NOT NULL,
    valeur INTEGER NOT NULL,
    date_transfert TEXT NOT NULL,

    FOREIGN KEY(client_source) REFERENCES clients(id),
    FOREIGN KEY(client_destination) REFERENCES clients(id)
);

CREATE VIEW vue_historique_transaction AS
SELECT
    t.id,
    c.nom,
    c.numero,
    o.libelle AS operation,
    t.valeur,
    t.frais,
    t.date_transaction
FROM transactions t
JOIN clients c
ON t.client_id = c.id
JOIN type_operation o
ON t.type_operation_id = o.id;

CREATE VIEW vue_solde_client AS
SELECT
    c.id,
    c.nom,
    c.numero,
    SUM(
        CASE
            WHEN o.libelle='DEPOT'
            THEN t.valeur

            WHEN o.libelle='RETRAIT'
            THEN -(t.valeur + t.frais)

            ELSE 0
        END
    ) AS solde
FROM clients c
LEFT JOIN transactions t
ON c.id=t.client_id
LEFT JOIN type_operation o
ON t.type_operation_id=o.id
GROUP BY c.id;

INSERT INTO operateur(libelle)
VALUES
('Notre operateur'),('Autre operateur');

INSERT INTO prefixe(prefixe, operateur)
VALUES
(33, 1),
(37, 1),
(38, 1);

INSERT INTO prefixe(prefixe, operateur)
VALUES
(32, 2),
(34, 2),
(31, 2);

INSERT INTO frais_autre_operateur(operateur, frais)
VALUES
(2, 5.5);


-- Types d'opérations
INSERT INTO type_operation(libelle)
VALUES
('DEPOT'),
('RETRAIT'),
('TRANSFERT');


-- Barème dépôt
INSERT INTO bareme
(valeur_min, valeur_max, frais, type_operation_id)
VALUES
(100,10000,0,1),
(10001,50000,0,1),
(50001,200000,0,1);


-- Barème retrait
INSERT INTO bareme
(valeur_min, valeur_max, frais, type_operation_id)
VALUES
(100,10000,100,2),
(10001,50000,200,2),
(50001,200000,500,2);


-- Barème transfert
INSERT INTO bareme
(valeur_min, valeur_max, frais, type_operation_id)
VALUES
(100,10000,100,3),
(10001,50000,200,3),
(50001,200000,500,3);


-- Clients
INSERT INTO clients
(nom, numero, date_creation)
VALUES
('Rakoto Jean',371234567,'2026-07-20'),
('Rabe Marie',382345678,'2026-07-20'),
('Andry Dupont',331234567,'2026-07-20'),
('Soa Ranaivo',372223344,'2026-07-20'),
('Mamy Razafy',383334455,'2026-07-20');

INSERT INTO transactions
(client_id, type_operation_id, valeur, frais, date_transaction)
VALUES
(1, 1, 5000,   50,  '2026-07-20 08:00:00'),
(2, 1, 25000, 100,  '2026-07-20 08:15:00'),
(3, 1, 75000, 200,  '2026-07-20 09:00:00'),
(4, 1, 120000,200,  '2026-07-20 09:30:00'),
(5, 1, 9000,   50,  '2026-07-20 10:00:00'),

(1, 2, 3000,   100, '2026-07-20 10:30:00'),
(2, 2, 18000,  200, '2026-07-20 11:00:00'),
(3, 2, 60000,  500, '2026-07-20 11:30:00'),
(4, 2, 8000,   100, '2026-07-20 12:00:00'),
(5, 2, 45000,  200, '2026-07-20 12:30:00'),

(1, 1, 180000, 200, '2026-07-21 08:00:00'),
(2, 2, 9500,   100, '2026-07-21 08:30:00'),
(4, 1, 15000,  100, '2026-07-21 09:30:00'),
(5, 2, 120000, 500, '2026-07-21 10:00:00');


CREATE VIEW vue_total_gain_operation AS
SELECT
    o.libelle AS type_operation,
    COUNT(*) AS nombre_transactions,
    SUM(t.frais) AS gain_total
FROM transactions t
JOIN type_operation o
    ON t.type_operation_id = o.id
WHERE t.operateur_id = 1
GROUP BY o.id, o.libelle;

CREATE VIEW vue_gain_autre_operateur AS
SELECT
    op.libelle AS operateur,
    COUNT(*) AS nombre_transactions,
    SUM(t.frais) AS gain_total
FROM transactions t
JOIN operateur op
    ON t.operateur_id = op.id
WHERE t.operateur_id != 1
GROUP BY op.id, op.libelle;

CREATE VIEW vue_montant_autre_operateur AS
SELECT
    op.libelle AS operateur,
    COUNT(*) AS nombre_transactions,
    SUM(t.valeur - t.frais) AS montant_total
FROM transactions t
JOIN operateur op
    ON t.operateur_id = op.id
WHERE t.operateur_id != 1
GROUP BY op.id, op.libelle;


CREATE TABLE bonus(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pourcentage  DECIMAL(5,2) NOT NULL DEFAULT 10,
    actif BOOLEAN DEFAULT 1
);

INSERT INTO bonus (pourcentage , actif) VALUES (10,1);

CREATE TABLE epargne
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    epargne DECIMAL(10,2) NOT NULL DEFAULT 0,
    client_id INTEGER NOT NULL,
    FOREIGN KEY(client_id) REFERENCES clients(id)
);

CREATE TABLE epargne_client
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    transaction_id INTEGER NOT NULL,
    date_epargne DATE NOT NULL,
    FOREIGN KEY(client_id) REFERENCES clients(id),
    FOREIGN KEY(transaction_id) REFERENCES transactions(id)
);
