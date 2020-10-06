# Matrice

## About project

Účelom tohoto projektu je si vyskúšať niektoré programovacie koncepty. Projekt používa minimalistický PHP framework [mezzio](https://github.com/mezzio/mezzio) (bývalý Zend Expressive), ktorý je [PSR-15](https://www.php-fig.org/psr/psr-15/) kompatibilný, t.j na spracovanie requestov využíva systém middleware.

Názov *Matrice* (La Matrice) je z taliančiny a znamená matica. Zvolil som ho pretože aj názov frameworku je z taliančiny (Mezzio). V kóde ale používam anglické Skillmatrix.

## Project domain

Skill = zručnosť, schopnosť, vedomosť.  
V projekte riešim vytváranie tzv. matice skillov. Matica skillov je tabuľka kde je každému človeku priradené hodnotenie (skóre) k určitej zručnosti.

Príklad matice skillov:

```bash
+--------+----------------------+-------------------+-----------------------+
|        | Programming Language | Database Concepts | Debugging & Profiling |
+---------------------------------------------------------------------------+
| Tomas  |          1           |         1         |           2           |
+---------------------------------------------------------------------------+
| Peter  |          4           |         2         |           4           |
+---------------------------------------------------------------------------+
| Michal |          5           |         3         |           3           |
+--------+----------------------+-------------------+-----------------------+
```

Riešená je iba backendová časť vo forme REST API.


## Persistence layer

Projekt používa repository pattern (interface SkillmatrixRepository), konkrétne sú dáta ukladané do MySQL databáze cez Doctrine (DoctrineSkillmatrixRepository implements SkillmatrixRepository).

Dáta v databáze sú pre zjednodušenie uložené vo "flat" podobe v jednej tabuľke *skillmatrix*. Entity *Person*, *Skill*, *Rating* nemajú vlastné DB tabuľky ale sú uložené ako JSON v tabuľke *skillmatrix*.

Všetky ID sú typu UUID:
 - ID entity dá sa generovať ešte pred vložením do DB
 - UUID je jedinečné pre všetky entity naprieč tabuľami, serverami
 - je bezpečné použiť ID v URL, nejde uhádnuť ďalší záznam v DB ako u auto-incremental primary key

**TODO**: Teraz je v MySQL skillmatrix ID stĺpec typu CHAR(36). To je neefektívne z pohľadu indexu, lepšie je BINARY(16), MySQL 8 má funkcie na ľahšie použitie UUID. U ostatných entít to je jedno pretože nemajú vlastnú tabuľku. Pre entitu *Skill* je prirodzenejšie ID typu natural (Programming Language => programming_language), nie UUID.

ID entity generuje Repository metódou *nextIdentity()* pretože ID entity by som mal vedieť vygenerovať ešte pred uložením entity do repository (entita by mala byť validná hneď po vytvorení, nie až po uložení). Keď generujem ID entity v repository môžem tak implementovať nielen gnerovanie ID typu UUID ale aj sekvencie alebo auto-increment.

## Command Bus

V projekte používam pre väčšiu prehladnosť na vykonanie akcii pattern [Command Bus](https://en.wikipedia.org/wiki/Command_pattern). Command je objekt s dátami, ktoré určujú čo chce user vykonať. Handler je kód ktorý vykonáva daný Command. Každý Command má presne jeden handler. Používam knižnicu [thephpleague/tactician](https://github.com/thephpleague/tactician).

## Validation

Na validovanie vstupných dát používam knižnicu [beberlei/assert](https://github.com/beberlei/assert) upravenú tak aby vyhadzovala výnimky vo formáte *ProblemDetails*. 

## REST API

Dokumentácia API vo formáte [API Blueprint](docs/api.apib) a vo formáte [HTML](docs/api.html).

Telo requestu (ak sa posiela) je typu JSON (application/json), response je typu [JSON HAL](https://tools.ietf.org/html/draft-kelly-json-hal-08) (application/hal+json).
Použitá je knižnica [mezzio/mezzio-hal](https://github.com/mezzio/mezzio-hal).

Chybové stavy sa vracajú podľa štandardu [Problem Details](https://tools.ietf.org/html/rfc7807) (application/problem+json).
Použitá je knižnica [mezzio/mezzio-problem-details](https://github.com/mezzio/mezzio-problem-details).

Na testovanie API pri vývoji je dobrý nástroj Postman.

### Vytvorenie novej matice:

```bash
POST /skillmatrix
```

Body:  
```bash
{
	"persons": [
		{
			"id": "1df4a735-88b3-4590-a1fe-cde6e8c1bd73",
            "name": "Michal"
		},
		{
			"id": "71fb2218-c6e4-4293-a590-399fe7de70c4",
			"name": "Vlado"
		},
		{
			"id": "f1cdd62e-7df1-4f69-9acf-0ea80b98c52a",
			"name": "Tibor"
		}
	],
	"skills": [
		{
			"id": "0ae8b2a5-9b88-41a0-acd6-e929578de254",
			"name": "Programming Language"
		},
		{
			"id": "482362ba-fa9d-497a-b91a-f11547ca09e6",
			"name": "Database Concepts"
		},
		{
			"id": "a9b10288-6381-4f2a-9e9e-96f6bbc96bf2",
			"name": "Debugging & Profiling"
		},
		{
			"id": "e92d2416-a58e-4b59-b6dc-eae991142366",
			"name": "Client-side Scripting"
		}
	]
}
```

### Pridanie hodnotenia do matice:

Jedna osoba v matici môže mať priradené len jedno hodnotenie ku každému skillu. Ak hodnotenie už existuje vyhodím výnimku *RatingAlreadyExists*.

```bash
PUT /skillmatrix/{{matrixId}}/ratings
```

Body:  
```bash
{
	"personId": "1df4a735-88b3-4590-a1fe-cde6e8c1bd73",
	"skillId": "0ae8b2a5-9b88-41a0-acd6-e929578de254",
	"reviewer": {
		"id": "2a264d46-2ca0-4bb2-8c79-b0b5aa7aec28",
		"name": "Tomas"
	},
	"score": 3,
	"note": "Test rating 1"
}
```

### Získanie matice:

```bash
GET /skillmatrix/{{matrixId}}
```

Response:

```bash
{
    "id": "50483c18-d5a4-4230-b253-4b1d962da756",
    "persons": [
        {
            "id": "1df4a735-88b3-4590-a1fe-cde6e8c1bd73",
            "name": "Michal"
        },
        {
            "id": "71fb2218-c6e4-4293-a590-399fe7de70c4",
            "name": "Vlado"
        },
        {
            "id": "f1cdd62e-7df1-4f69-9acf-0ea80b98c52a",
            "name": "Tibor"
        }
    ],
    "skills": [
        {
            "id": "0ae8b2a5-9b88-41a0-acd6-e929578de254",
            "name": "Programming Language"
        },
        {
            "id": "482362ba-fa9d-497a-b91a-f11547ca09e6",
            "name": "Database Concepts"
        },
        {
            "id": "a9b10288-6381-4f2a-9e9e-96f6bbc96bf2",
            "name": "Debugging & Profiling"
        },
        {
            "id": "e92d2416-a58e-4b59-b6dc-eae991142366",
            "name": "Client-side Scripting"
        }
    ],
    "ratings": [
        {
            "personId": "1df4a735-88b3-4590-a1fe-cde6e8c1bd73",
            "skillId": "0ae8b2a5-9b88-41a0-acd6-e929578de254",
            "reviewer": {
                "id": "2a264d46-2ca0-4bb2-8c79-b0b5aa7aec28",
                "name": "Tomas"
            },
            "score": 3,
            "note": "Test rating 1",
            "created": "2020-03-17T20:20:31+00:00"
        }
    ],
    "_links": {
        "self": {
            "href": "http://127.0.0.1:10100/skillmatrix/50483c18-d5a4-4230-b253-4b1d962da756"
        }
    }
}
```

## Installation

1. Naklonovať GIT repozitár:  
`git clone https://github.com/demijohn/matrice.git`

2. Spustiť Docker:  
`docker-compose up -d`  
`docker-compose exec app bash`

3. Nainštalovať závislosti:  
 `composer install`

4. Vytvoriť .env súbor (prekopírovaním z distribučného .env.local):    
`cp .env.local .env`  

5. Spustiť databázové migrácie:  
`./vendor/bin/doctrine-migrations migrations:migrate`

Databáza *matrice* sa vytvorí automaticky namountovaním súboru:  
`./docker/mysql-init/create_schemas.sql`  
ako entry pointu pre MySQL kontainer (viď `docker-compose.yml`). 

## Tests

Používám [PHPUnit](https://github.com/sebastianbergmann/phpunit). Spustenie testov:  
`./vendor/bin/phpunit`

Zatiaľ mám len jeden integračný test (CreateSkillmatrixActionTest), ktorý testuje response z API a kontroluje vrátený JSON. Na integračné testy som si vytvoril vlastnú triedu *TestCase* (extenduje TestCase PHPUnitu). V tejto triede sú metódy (get(), post(), patch()) na volanie API a metóda assertResponse() na testovanie response. V testoch sa API nevolá cez vrstvu HTTP ale priamo sa vytvoria objekty Request a posielajú sa do frameworku (handle() metóda z Mezzio\Application).

```
Create Skillmatrix Action (MatriceTest\Integration\Action\CreateSkillmatrixAction)
 ✔ Create skillmatrix  1381 ms
 ✔ Create skillmatrix with missing parameters  59 ms
 ✔ Create skillmatrix with invalid parameters  50 ms

Time: 00:01.521, Memory: 6.00 MB

OK (3 tests, 24 assertions)
```

**TODO**: Napísať aj nejaký unit test.

## Static Analysis

Na statickú analýzu kódu používam [PHPStan](https://github.com/phpstan/phpstan) na max. level. Spúšta sa:  
`./vendor/bin/phpstan analyse`

**TODO**: Vytvoriť v *composer.json* skript na spúštanie PHPStan-u.

## Coding Style

Na udržiavanie jednotného štýlu zdrojového kódu používam [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer). Spúšta sa:  
`./vendor/bin/phpcs -p -s`

**TODO**: Vytvoriť v *composer.json* skript na spúštanie PHP_CodeSniffer-u.