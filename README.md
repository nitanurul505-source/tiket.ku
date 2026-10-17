# TiketKu - Campus Event Ticket Platform (Dockerized)

## 1. Project Overview and Main Features

TiketKu (tiket.ku) is a PHP web application for campus event ticketing.

Main features:

- Browse events and view event details
- Register and log in
- Book tickets for an event and cancel them
- Payment confirmation / rejection by the administrator
- Create, edit and delete events (with poster upload)

Stack: PHP 8.2 + Apache, MariaDB 10.4, Docker Compose.

Repository: https://github.com/nitanurul505-source/tiket.ku

## 2. Prerequisites and Docker Version Tested

- Docker Desktop with Docker Compose v2
  (check with `docker version` and `docker compose version`)
- **Tested with Docker version: 28.5.1 (build e180ab8)**
- Git

## 3. Clone the Repository and Configure Environment Variables

Clone the repository and switch to the Dockerized branch:

```
git clone https://github.com/nitanurul505-source/tiket.ku.git
cd tiket.ku
git checkout chore/dockerize-project
```

All Docker files (`Dockerfile`, `compose.yaml`, `.env.example`) are in the
repository root, next to the `event_ticketing/` folder (the application source).
Run all the commands below from the repository root.

Create your local `.env` from the example file:

```
cp .env.example .env              # macOS / Linux / Git Bash
copy .env.example .env            # Windows CMD
Copy-Item .env.example .env       # Windows PowerShell
```

Then open `.env` and set your own values (see section 4).
Never commit your real `.env` file.

## 4. Required Environment Variables (`.env.example`)

The file `.env.example` defines these variable names. Your `.env` must contain
all of them:

| Variable | Description | Default (example) |
| --- | --- | --- |
| `DB_NAME` | Database name | `tiketku` |
| `DB_USER` | Database user (keep as `root`) | `root` |
| `DB_PASS` | Database password (also used as the MariaDB root password) | `change_me_dev_only` |

`DB_HOST` is not set in `.env`. It is fixed to `db` (the Compose service name)
in `compose.yaml`.

## 5. Commands to Build and Start

```
docker compose up --build -d
```

The first start takes a little longer because the database is initialized
from `event_ticketing/tiketku.sql`.

## 6. App URL, Host Port and Container Port

| Item | Value |
| --- | --- |
| App URL | http://localhost:8080/ |
| Host port | 8080 |
| Container port | 80 |
| Port mapping | `8080:80` (host -> container) |

- The root URL redirects automatically to the event list at
  `/event_ticketing/public/index.php?controller=event&action=index`.
- The database runs inside Docker only (it is not exposed to the host).

## 7. How to Verify (Expected Output)

| ID | Action | Expected result |
| --- | --- | --- |
| V-01 | `docker compose ps` | `app` is running (it shows `healthy` after about 30 seconds) and `db` is `healthy` |
| V-02 | Open http://localhost:8080/ | The TiketKu home page loads with **All Events (4)** and no errors |
| V-03 | Click **Login** and sign in with `budi@example.com` / `budi123` | Login succeeds, you are redirected to the event list and **Log out** appears in the navbar |
| V-04 | `docker compose restart` | The app starts again and V-02 still passes |

Demo accounts come from `tiketku.sql` and are for local development only.
Administrator demo account: `admin@tiketku.com` / `admin123`.

## 8. Commands to View Logs and Status

```
docker compose ps                # status of the app and db services
docker compose logs              # logs from all services
docker compose logs -f app       # follow the app logs
docker compose logs -f db        # follow the database logs
```

## 9. How to Stop and Clean Up

```
docker compose down        # stop and remove containers
docker compose down -v     # also delete the database and upload volumes
```

Note: `docker compose down -v` permanently deletes the database data and any
uploaded event posters (the `uploads` volume).

## 10. Troubleshooting Notes and Known Limitations

### Troubleshooting

- **Port 8080 already in use:** change `"8080:80"` in `compose.yaml`.
- **"Connection error" on the page:** make sure `.env` exists in the same folder
  as `compose.yaml` (the repository root) and that the `db` service is healthy
  (`docker compose ps`).
- **Changed `.env` or `tiketku.sql` but nothing changed:** the database is only
  initialized on an empty volume. Run `docker compose down -v`, then start again.
- **Warning about blank variables:** you forgot to create `.env` from `.env.example`.

### Known limitations

- Uploaded event posters are stored in the `uploads` volume and are deleted by
  `docker compose down -v`.
- The demo data in `tiketku.sql` uses plain-text passwords. This setup is meant
  for local development and handover only, not production.
