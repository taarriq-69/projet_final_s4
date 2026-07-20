# Cote Operateur

## Configuration des prefixes **(Zax)**

### Model
- PrefixeModel :
    * [X] regles de validation
        * [X] 3 chiffre(min,max)
        * [X] 2 premier chiffre 03 (CustomRules)
        * [X] unique

- CustomRules :
    * [X] validerPrefixe()

### Controller
- Pas encore

### View
- Pas encore

## Situation Gain via different frais

### Model
- Pas de Model

### Controller **(Zax)**
* [X] Creer vue calcul_total_frais
* [X] filtreParOperation(id_operation , string rechercher)

### View **(Taarriq)**
* [x] afficher les total des gains par operation et gain global
* [x] Filtre par operation et recherche global


## Situation des compte clients

### Model
- Pas encore

### Controller **(Taarriq)**
* [x] listeClient()
* [x] voirTransactionClient(id_client)

### View **(Zax)**
* [x] Faire un tableau de liste de client
* [x] Faire la page de detail client ou il y a ses transaction


# Cote client

## Login

### Model **(Zax)**
* [x] ClientModel
    * [x] regles de validation
        * [x] prefixe valide (customRules)
        * [x] 10 chiffres minimum maximum
        * [x] unique

### Controller
- pas encore

### View **(Taarriq)**
* [x] Faire une page de login avec mots de passe mais pas encore de validation de mots de passe


## Operation

### Model
- pas encore

### Controller
* [x] voirSolde(vue calcul_solde) **(Zax)**
* [x] faireUnDepot(id_client,date,valeur) **(Taarriq)**
* [x] faireUnRetrait(id_client,date,valeur) **(Taarriq)**
* [x] faireUnTransfert(id_client,date,valeur) **(Zax)**
* [x] voirHistorique(id_operation,id_client,date) **(Taarriq)**

### View
* [x] Page pour depot **(Taarriq)**
* [x] Page pour transfert **(Zax)**
* [x] Page pour retrait  **(Taarriq)**
* [x] Page pour voir les historiques avec filtre **(Taarriq)**





#  Version 2
## Cote Operateur
### Configuration des prefixes valables pour les autres operateurs (Taarriq)
* [] PrefixeModel 
    * [] CustomRules : assigner un  ou plusieur  prefixe pour chaque operateur

### Configuration en % des transfers vers les autres operateurs (Taarriq)
* [] base
* [] fonction getConfigurationAutreOperateur()

### Gain via les differents frais (Izaia)
* [] Ajouter une nouvelle vue qui prend les gains par operateur
* [] totalGainOperateur(id_operateur)

### Situation des montants à envoyer à chaque opérateur (Izaia)
* [] getTotalMontantAutreOperateur()

## Cote client (Izaia)
### Option inclure frais de retrait lors de l'envoie
* [] prendreFraisRetrait(valeur)
* [] Afficher l'option dans la vue 
* [] ajouter le frais de retrait a l'argent envoyer
* [] Désactiver l'option si le destinataire est un autre opérateur (pas de frais de retrait dans ce cas)


### Envoi multiple vers plusieurs numéro (Taarriq)
* [] ajouter plusieur champ de numero au transfert(dynamique)
* [] Vérifier que tous les numéros sont du même opérateur, sinon rejeter
* [] diviser le montant / nombre numero
* [] faire le transfert pour chaque numero : transfertMultiple(tableau[numero], montant )