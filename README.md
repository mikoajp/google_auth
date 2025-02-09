# Projekt Symfony z OAuth2
## ⚙️ Konfiguracja środowiska
### Kroki startowe:
1. Skopiuj szablon środowiska developerskiego:
   ```bash
   cp .env.dev .env
   ```
2. Edytuj kluczowe wartości w pliku `.env`:
   ```bash
   nano .env
   ```
   Najważniejsze zmienne środowiskowe:
   - **Google OAuth (wymagane):**
     ```env
     OAUTH_GOOGLE_CLIENT_ID=wstaw_id_z_google_console
     OAUTH_GOOGLE_CLIENT_SECRET=wstaw_secret_z_google_console
     ```
   - **Baza danych:**
     ```env
     DATABASE_URL="postgresql://user:password@database:5432/my_app_db?serverVersion=15&charset=utf8"
     ```
   - **Sekret aplikacji (generowanie przez `openssl`):**
     ```bash
     openssl rand -hex 32
     ```
     ```env
     APP_SECRET=wygenerowany_sekret
     ```
### Uruchomienie lokalne:
1. **Instalacja zależności:**
   ```bash
   composer install
   ```
2. **Konfiguracja bazy danych:**
   ```bash
   docker-compose build
   docker-compose up
   php bin/consol doctrine:database:create
   php bin/consol doctrine:migrations:migrate
   php bin/console doctrine:schema:update
   ```
3. **Uruchomienie serwera developerskiego:**
   ```bash
   symfony serve:start
   ```
4. **Przetestuj logowanie przez Google:**
   Otwórz w przeglądarce [http://localhost:8000/](http://localhost:8000/).
---
## ➕ Dodawanie nowego dostawcy OAuth (np. Facebook)
1. **Zainstaluj klienta Facebook OAuth:**
   ```bash
   composer require league/oauth2-facebook
   ```
2. **Utwórz nowy Authenticator:**
   Plik: `src/Security/FacebookAuthenticator.php`
   Przykładowa implementacja:
   ```php
   <?php
   namespace App\Security;
   use KnpU\OAuth2ClientBundle\Security\AbstractOAuthAuthenticator;
   use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
   class FacebookAuthenticator extends AbstractOAuthAuthenticator
   {
       protected function getProviderName(): string {
           return 'facebook';
       }
       protected function getOAuthUserData($owner): array {
           return [
               'id' => $owner->getId(),
               'email' => $owner->getEmail()
           ];
       }
   }
   ```
3. **Dodaj konfigurację w `knpu_oauth2_client.yaml`:**
   ```yaml
   facebook:
       type: facebook
       client_id: '%env(OAUTH_FACEBOOK_CLIENT_ID)%'
       client_secret: '%env(OAUTH_FACEBOOK_CLIENT_SECRET)%'
       redirect_route: connect_provider_check
       redirect_params: { provider: facebook }
   ```
4. **Dodaj zmienne środowiskowe w `.env`:**
   ```env
   OAUTH_FACEBOOK_CLIENT_ID=twoje_id
   OAUTH_FACEBOOK_CLIENT_SECRET=twoje_secret
   ```
5. **Zarejestruj serwis w `services.yaml`:**
   ```yaml
   App\Security\FacebookAuthenticator:
       tags: [security.authenticator]
   ```
---
### ℹ️ Dodatkowe informacje:
- Porty bazy danych:
  - MySQL: **3306**
  - PostgreSQL: **5432**
- **Google Client ID/Secret:** Zdobądź w [Google Cloud Console](https://console.cloud.google.com) pod sekcją: "APIs & Services" → "Credentials".
- **Dane do Facebook OAuth:**
  1. Zarejestruj aplikację na stronie [Facebook for Developers](https://developers.facebook.com/).
  2. W sekcji "Settings → Basic" znajdziesz **App ID** i **App Secret**.
---
### 🛑 Ważne:
Nigdy nie commituj pliku `.env` do repozytorium!
