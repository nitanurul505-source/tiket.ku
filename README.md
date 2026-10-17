# TiketKu - Campus Event Ticket Platform (Dockerized)

## 1. Overview

TiketKu (tiket.ku) is a PHP web application for campus event ticketing.

Main features:

- Browse events and view event details
- Register and log in
- Book tickets for an event and cancel them
- Payment confirmation / rejection by the administrator
- Create, edit and delete events (with poster upload)

Stack: PHP 8.2 + Apache, MariaDB 10.4, Docker Compose.

Repository: https://github.com/nitanurul505-source/tiket.ku

## 2. Prerequisites

- Docker Desktop with Docker Compose v2
  (check with `docker version` and `docker compose version`)
- Tested with Docker version: 28.5.1 (build e180ab8)
- Git

## 3. Clone and configure

```
git clone https://github.com/nitanurul505-source/tiket.ku.git
cd tiket.ku
git checkout chore/dockerize-project
```

All Docker files (`Dockerfile`, `compose.yaml`, `.env.example`) are in the
repository root, next to the `event_ticketing/` folder (the application source).
Run all the commands below from the repository root.

Create your local `.env` from the example:

```
cp .env.example .env              # macOS / Linux / Git Bash
copy .env.example .env            # Windows CMD
Copy-Item .env.example .env       # Windows PowerShell
```

| Variable | Description | Default (example) |
| --- | --- | --- |
| DB_NAME | Database name | tiketku |
| DB_USER | Database user (keep as `root`) | root |
| DB_PASS | Database password (also used as the MariaDB root password) | change_me_dev_only |

`DB_HOST` is set to `db` (the Compose service name) in `compose.yaml`.
Never commit your real `.env` file.

## 4. Build and start

```
docker compose up --build -d
```

The first start takes a little longer because the database is initialized
from `event_ticketing/tiketku.sql`.

## 5. Access the app

- URL: http://localhost:8080/
  (it redirects automatically to the event list at
  `/event_ticketing/public/index.php?controller=event&action=index`)
- Host port: 8080 -> container port: 80
- The database runs inside Docker only (it is not exposed to the host).

## 6. Verify the app works

| ID | Action | Expected result |
| --- | --- | --- |
| V-01 | `docker compose ps` | `app` is running (it shows `healthy` after about 30 seconds) and `db` is `healthy` |
| V-02 | Open http://localhost:8080/ | The TiketKu home page loads with **All Events (4)** and no errors |
| V-03 | Click **Login** and sign in with `budi@example.com` / `budi123` | Login succeeds, you are redirected to the event list and **Log out** appears in the navbar |
| V-04 | `docker compose restart` | The app starts again and V-02 still passes |

Demo accounts come from `tiketku.sql` and are for local development only.
Administrator demo account: `admin@tiketku.com` / `admin123`.

## 7. Logs and status

```
docker compose ps
docker compose logs
docker compose logs -f app
```

Use `docker compose logs -f db` to follow the database logs.

## 8. Stop and clean up

```
docker compose down        # stop and remove containers
docker compose down -v     # also delete the database and upload volumes
```

## 9. Troubleshooting and known limitations

- **Port 8080 already in use:** change `"8080:80"` in `compose.yaml`.
- **"Connection error" on the page:** make sure `.env` exists in the same folder
  as `compose.yaml` (the repository root) and that the `db` service is healthy
  (`docker compose ps`).
- **Changed `.env` or `tiketku.sql` but nothing changed:** the database is only
  initialized on an empty volume. Run `docker compose down -v`, then start again.
- **Warning about blank variables:** you forgot to create `.env` from `.env.example`.
- Uploaded event posters are stored in the `uploads` volume and are deleted by
  `docker compose down -v`.
- Known limitation: the demo data in `tiketku.sql` uses plain-text passwords.
  This setup is meant for local development and handover only, not production.
