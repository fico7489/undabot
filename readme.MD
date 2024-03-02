# Undabot zadatak za "Senior Software Engineer" - 1.3.2024. - Filip Horvat

[PDF Zadatak](/docs/Zadatak-PHP.pdf)

## Set up project

1. git clone https://github.com/fico7489/undabot
2. cd undabot
3. create .env.local file and store your YOUR_GITHUB_TOKEN and YOUR_GITHUB_ISSUE_URL

- APP_TEXT_TERM_PROVIDER_GITHUB_ISSUE_TOKEN=[YOUR_GITHUB_TOKEN]
- APP_TEXT_TERM_PROVIDER_GITHUB_ISSUE_URL=[YOUR_GITHUB_ISSUE_URL]

Example:

- APP_TEXT_TERM_PROVIDER_GITHUB_ISSUE_TOKEN=1234_dummy_github_token_dummy_1234
- APP_TEXT_TERM_PROVIDER_GITHUB_ISSUE_URL=/repos/fico7489/test/issues/1

4. docker compose up -d
5. docker compose exec php sh
6. composer install
7. php bin/console doctrine:migrations:migrate  --no-interaction
8. api endpoint for search:

 - http://localhost:5004/api/text_terms/score?term=[YOUR_TERM]
 - http://localhost:5004/api/text_terms/score?term=php

DONE!

#### Worked environment:

- Linux: 22.04.4 LTS
- Docker: 25.0.3

#### Score calculation

- Example: "test php sucks sd asd php sucks adaphp aaa php sucks,, php rocks done."
- If term is "php", we have 1 "php rocks" and 3 "php sucks" so it is 1+3= 4, and then 1 / 4 = 25% -> 2.5 because result must be in [1-10]

### Other

#### Recreate database

1. php bin/console doctrine:schema:drop --force --full-database
2. php bin/console doctrine:migrations:migrate  --no-interaction

#### Run csfixer, phpstan and tests:

- vendor/bin/php-cs-fixer fix
- vendor/bin/phpstan analyse
- vendor/bin/phpunit

#### OpenAPI 3 swager documentation

1. php bin/console asset:install
2. visit: http://localhost:5004/api/docs

#### API v2 endpoint:

Run in terminal or import to POSTMAN:

```bash
curl --location 'http://localhost:5004/api/v2/text_terms?include=text' \
    --header 'Accept: application/vnd.api+json' \
    --header 'Content-Type: application/json' \
    --data '{
    "term" : "php"
}'
```

## Technical notes

### Basic:

 - Servisi na projektu su php 8.2, mysql 8 i nginx 1.23
 - PHP koristi Symfony 7 framework, API je napravljen sa "API Platform" -> https://api-platform.com/

***

### Code Quality:

- "friendsofphp/php-cs-fixer" za ispravljanje code style
- "phpstan/phpstan" - za stabilnost koda, pronalazak bugova i problema u kodu
- "phpunit/phpunit" - za testove

***

### Testovi:

- Sa Unit i Funkcionalnim testovima su pokriveni servisi i controller.

***

### Entiteti

- Imamo dva entiteta "Text" i "TextTerm"
- U "Text" spremamo dohvaćeni tekst za određenog providera na određenom url-u tako da se dohvaća samo jednom i spremi za buduće pretrage, primjer:
  - za providera GithubIssue na url-u /repos/username/repo/issues/1  text je "test"
  - za providera GithubIssue na url-u /repos/username/repo/issues/2  text je "test2"
  - za providera TwitterTweet na url-u /tweets/username/1  text je "test3"

- Na entitetu "TextTerm" spremamo "Text" entitet, term i njegov score, ako se ubuduće radi pretraga za isti term on se dohvaća iz baze:
  - ako si pretražio "php" i "js" na istom provideru i istom linku bit će dva zapisa u "TextTerm" i jedan u "Text"

***

### Servisi

- Servisi su namespejsani sa "TextTerm" jer to je kao zamišljeni naziv ovog sustava

- ScoreCalculator.php - računa score za pojedini text i term i vraća "TextTerm", uzima ako već postoji ako ne onda ga kreira, isto tako i "Term"

- TextProvider.php folder je interface za providera koji nam daje tekst za konkretnu implementaciju

  - GithubIssueTextProvider.php - je provider za "github issue"
  - TwitterTweetTextProvider.php je dodatni primjer kako se može dodati novi

- TextProvider.php interface je injectan u konstruktoru TextTermProvider.php servisa, a u services.yaml je definirano koju klasu da symfony injecta u konstruktoru:

```
    App\Service\TextTerm\TextProvider\TextProviderInterface:
        class: App\Service\TextTerm\TextProvider\GithubIssueTextProvider
```

- Ako želimo promijeniti GithubIssueTextProvider na TwitterTweetTextProvider: 

```bash
    App\Service\TextTerm\TextProvider\TextProviderInterface:
        class: App\Service\TextTerm\TextProvider\TwitterTweetTextProvider
```

- Aplikacije će bez promjene koda početi koristiti novi provider, samo sa promjenom konfiguracije.

- TextTermProvider.php je servis koji dohvaća "TextTerm" entitet, kreira novi ili dohvati postojeći 
  - tu se dohvaća tekst od providera i onda se računa score za dani text i term
  - ono što se dohvati sa providera i score koji se izračunaju se spremaju u entitete "Text" i "TextTerm"

***

### API endpoint - controller

- Imamo dva api endpointa i dva kontrolera

- TextTermScoreController.php se koristi za custom api endpoint na api platformu i on je idempotentan, tj. uvijek vrati TextTerm 
 bez obzira da li se kreira ili vrati postojeći i o tome brine servis TextTermProvider.php

- TextTermV2Controller.php on radi isto samo je on definiran na v2 ruti i na njega se ne šalje term u url parametru kao na prethodnom, nego u JSON-u 
 te se onda to serializira u TextTerm entitet i term se dohvaća iz entiteta


## Bonus

- Uskladiti API s JSONAPI specifikacijom
  - napravljeno! see -> posložen je api platform da po defaultu vraća JSONAPI application/vnd.api+json
- API v2 - uvesti verzioniranje API-a
  - napravljeno! imamo api v2 api endpoint za pretragu, nije baš precizno bilo definirano kako izvesti valjda je to to 
- CI / CD - postaviti osnovni CI/CD
  - napravljeno! na repositoriju se vrti github workflow
- Docker - postaviti projekt koristeći Docker platformu
  - napravljeno!
- OpenAPI 3 specifikaciju projekta
  - napravljeno! see -> OpenAPI 3 swager documentation
- osnovni OAuth2 sustav bez korisnika
  - nije napravljeno!

## Additional notes

Nisam ništa pitao što bi eventualno bilo nejasno da ne gnjavim, nego sam sve napravio kako mi je izgledalo ok jer ipak je ovo samo testni zadatak.

Sustav pretražuje GitHub issue tako da uzme body od definiranog issue-a i na njemu računa score
Koji issue_url i github_token su u konfiguraciji i može se mijenjati. Ja sam shvatio da tako treba, 
a ne pretraživati više issue-a, uzimati komentare ili šta već.

Jedino nije napravljen "OAuth2 sustav" ali mogu i to ako treba.


