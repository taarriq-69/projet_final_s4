PRAGMA foreign_keys = ON;


CREATE TABLE prefixe
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe INTEGER NOT NULL UNIQUE
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

    FOREIGN KEY(client_id) REFERENCES clients(id),
    FOREIGN KEY(type_operation_id) REFERENCES type_operation(id)
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


CREATE TABLE login
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL UNIQUE,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,

    FOREIGN KEY(client_id) REFERENCES clients(id)
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
            THEN -t.valeur

            ELSE 0
        END
    ) AS solde

FROM clients c

LEFT JOIN transactions t
ON c.id=t.client_id

LEFT JOIN type_operation o
ON t.type_operation_id=o.id

GROUP BY c.id;