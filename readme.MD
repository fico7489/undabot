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















## Technical notes

## Additional notes



{riječ} rocks - broj_rezultata -> pozitivan = 7
{riječ} sucks - broj_rezultata -> negativan = 2


7 / 2 = 3.5

dana riječ "php"

"php rocks"
"php sucks"



github api: https://docs.github.com/en/rest/reference/search#search-issues-and-pull-requests

https://example.com/score?term=php

PHPUnit

 - osnovni OAuth2 sustav bez korisnika
 - Uskladiti API s JSONAPI specifikacijom
 - API v2 - uvesti verzioniranje API-a
 - CI / CD - postaviti osnovni CI/CD
 - Docker - postaviti projekt koristeći Docker platformu
 - OpenAPI 3 specifikaciju projekta




vendor/bin/php-cs-fixer fix
vendor/bin/phpstan analyse
vendor/bin/phpunit
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate  --no-interaction
php bin/console doctrine:schema:drop --force --full-database
php bin/console doctrine:schema:validate --skip-sync
php bin/console asset:install

--------
Technical notes

***
Basic:
Servisi na projektu su php 8.2, mysql 8 i nginx 1.23
PHP koristi Symfony 7 framework, API je napravljen sa "api platform" -> https://api-platform.com/
***

***
Code Quality:
"friendsofphp/php-cs-fixer" za ispravljanje code style
"phpstan/phpstan" - za stabilnost koda, pronalazak bugova i problema u kodu
"phpunit/phpunit" - za testove
***

***
Testovi:
Sa Unit i Funkcionalnim testovima su pokriveni servisi i controller.
***

***
Entiteti

Imamo dva entiteta "ProviderText" i "ProviderTextTerm"
U "ProviderText" spremamo(keširati) dohvaćeni tekst za određenog providera na određenom url-u
recimo za providera GithubIssue na url-u /repos/username/repo/issues/1  text je "test"
recimo za providera GithubIssue na url-u /repos/username/repo/issues/2  text je "test2"
recimo za providera TwitterTweet na url-u /tweets/username/1  text je "test3"

na entitetu "ProviderTextTerm" onda spremamo(keširamo)  term i njegov score za text

ako si pretražio "php" i "js" na istom provideru i istom linku bit će dva zapisa u "ProviderTextTerm" i jedan u "ProviderText"
***

***
Servisi

Servisi su namespejsani sa "TextTerm" jer to je kao zamišljeni naziv ovog sustava

ScoreCalculator.php - računa score za pojedini text i term

TextProvider folder sadrži interface za providera koji nam daje tekst i konkretne implementacije

GithubIssueTextProvider.php - je provider za "github issue", "TwitterTweetTextProvider" je dodatni primjer kako se može dodati novi

Text provider je injectan u konstruktoru preko interfejsa tako da se u services.yaml može zamijeniti servis:

    App\Service\TextTerm\TextProvider\TextProviderInterface:
        class: App\Service\TextTerm\TextProvider\GithubIssueTextProvider

u

    App\Service\TextTerm\TextProvider\TextProviderInterface:
        class: App\Service\TextTerm\TextProvider\TwitterTweetTextProvider

i aplikacije će bez promjene koda početi koristiti novi provider, samo sa promjenom konfiguracije.

TextTermProvider.php je servis koji dohvaća "ProviderText" entitet

tu se dohvaća tekst i računa score za dani text i term, ono što se dohvati sa providera i score koji se izračunaju se spremaju u entitete

servis vraća postojeći ili kreira novi entitet ali uvijek ga vraća.
***

***
API endpoint controller

ProviderTextTermScoreController.php se koristi za custom api endpoint na api platformu i on je idempotentan, tj. uvijek vrati ProviderText
bez obzira da li se kreira ili vrati postojeći i o tome brine servis: TextTermProvider
***

















--------
Sustav pretražuje GitHub issue tako da uzme body od definiranog issue-a i na njemu računa score
Koji user, repo, issue_id i github_token je u konfiguraciji i može se mijenjati ja sam shvatio da tako treba, 
a ne pretraživati više issue-a, uzimati komentare i sve ostalo.

Ako je term=php onda izračuna koliko se u body-u issue-a pojavljuje "php rocks" i "php sucks" zatim izračuna postotak 
i pomnoži sa 10 jer piše da razultat mora biti od 1-10, 5 znači 50%

Text fetcher je nazvan GithubIssue koji na sebi ima interfejs da se kasnije može zamijeniti sa GithubPullRequest, TwitterTweet, itd.

Imamo dva entiteta "ProviderText" i "ProviderTextTerm"

U "ProviderText" entitet se sprema text koji je dohvaćen za određen provider i na kojem url-u
recimo za GithubIssue na url-u: /issues/1
ili za TwitterTweet na url-u: /tweets/1

Spremljeni tekst u "ProviderText" entititu se onda koristi za računanje score-a a više term-sa.

------------------------------------------------------------------------
- OAuth - postaviti osnovni OAuth2 sustav bez korisnika (samo client credentials)
- Uskladiti API s JSONAPI specifikacijom (http://jsonapi.org/)
- API v2 - uvesti verzioniranje API-a i napraviti novu verziju endpointa za dohvat rezultata  koje odgovara JSONAPI specifikaciji (http://jsonapi.org/)
- CI / CD - postaviti osnovni CI/CD sustav koristeći neke od besplatnih CI sustava (npr. GitLab CI, https://travis-ci.org/)
- Docker - postaviti projekt koristeći Docker platformu
- Napraviti OpenAPI 3 specifikaciju projekta
------------------------------------------------------------------------




