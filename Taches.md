# Cote Operateur

## Configuration des prefixes **(Zax)**

### Model
- PrefixeModel :
    * [] regles de validation
        * [] 3 chiffre(min,max)
        * [] 2 premier chiffre 03 (CustomRules)
        * [] unique

- CustomRules :
    * [] validerPrefixe()

### Controller
- Pas encore

### View
- Pas encore


## Type d'operation **(Taarriq)**

### Model
* [] Creer l'OperationModel

### Controller
* [] Crud d'operation

### View
- Pas encore


## Situation Gain via different frais

### Model
- Pas de Model

### Controller **(Zax)**
* [] Creer vue calcul_total_frais
* [] filtreParOperation(id_operation , string rechercher)

### View **(Taarriq)**
* [] afficher les total des gains par operation et gain global
* [] Filtre par operation et recherche global


## Situation des compte clients

### Model
- Pas encore

### Controller **(Taarriq)**
* [] listeClient()
* [] voirTransactionClient(id_client)

### View **(Zax)**
* [] Faire un tableau de liste de client
* [] Faire la page de detail client ou il y a ses transaction


# Cote client

## Login

### Model **(Zax)**
* [] ClientModel
    * [] regles de validation
        * [] prefixe valide (customRules)
        * [x] 10 chiffres minimum maximum
        * [] unique

### Controller
- pas encore

### View **(Taarriq)**
* [] Faire une page de login avec mots de passe mais pas encore de validation de mots de passe


## Operation

### Model
- pas encore

### Controller
* [] voirSolde(vue calcul_solde) **(Zax)**
* [x] faireUnDepot(id_client,date,valeur) **(Taarriq)**
* [x] faireUnRetrait(id_client,date,valeur) **(Taarriq)**
* [] faireUnTransfert(id_client,date,valeur) **(Zax)**
* [x] voirHistorique(id_operation,id_client,date) **(Taarriq)**

### View
* [x] Page pour depot **(Taarriq)**
* [] Page pour transfert **(Zax)**
* [x] Page pour retrait  **(Taarriq)**
* [x] Page pour voir les historiques avec filtre **(Taarriq)**