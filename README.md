# Maruti Travels Portal — Setup Guide

This is the website that Maruti Travels uses internally to manage bookings,
customers, and packages. This guide walks you through getting it running on
your own computer so you can try it out or test changes.

You don't need to be a developer to follow these steps. You will be copying and
pasting commands into a black terminal window. If something doesn't work, jump
to the [Troubleshooting](#9-troubleshooting) section at the bottom.

**Total time: about 30–45 minutes the first time.**

---

## What you'll end up with

After following this guide:

- The Maruti Travels website will be running on your computer at
  <http://127.0.0.1:8000>.
- You'll be able to log in as an admin and try out the features.
- Nothing you do will affect the real, live website.

---

## Pick your operating system

- Using a **Mac**? → Go to [Section 1 — Mac setup](#1-mac-setup).
- Using **Windows**? → Go to [Section 2 — Windows setup](#2-windows-setup).

---

## 1. Mac setup

### Step 1.1 — Open the Terminal

Press **Cmd + Space**, type `Terminal`, and press **Enter**. A small window with
text will open. This is where you'll type the commands below.

> **Tip:** to run a command, click anywhere in the grey code box, copy it,
> paste it into Terminal, and press **Enter**. Wait for it to finish (the
> blinking cursor returns) before running the next one.

### Step 1.2 — Install Homebrew

Homebrew is a free tool that installs other tools for you. Copy this into
Terminal and press Enter:

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

It may ask for your Mac's password — type it (the screen won't show anything as
you type, that's normal) and press Enter. This step takes a few minutes.

When it's done, it may print two extra commands starting with `echo` and
`eval`. Copy and run those too if it asks.

Check it worked:

```bash
brew --version
```

You should see something like `Homebrew 4.x.x`. If you do, continue. If you
get "command not found", see [Troubleshooting](#9-troubleshooting).

### Step 1.3 — Install everything the project needs

Copy and run this one big command:

```bash
brew install php@8.2 composer mysql node git
```

This installs:
- **PHP 8.2** — the language the website is written in.
- **Composer** — installs PHP add-ons.
- **MySQL** — where the website stores its data.
- **Node** — needed to build the website's design (CSS / JavaScript).
- **Git** — used to download the project code.

This takes 5–15 minutes depending on your internet speed.

### Step 1.4 — Tell your Mac to use PHP 8.2

Run these two commands one after the other:

```bash
echo 'export PATH="/opt/homebrew/opt/php@8.2/bin:/opt/homebrew/opt/php@8.2/sbin:$PATH"' >> ~/.zshrc
```

```bash
source ~/.zshrc
```

> If you have an older Intel Mac (not Apple Silicon / M1/M2/M3/M4), replace
> `/opt/homebrew` with `/usr/local` in the first command.

Verify:

```bash
php -v
```

You should see `PHP 8.2.something`. If you see PHP 7 or PHP 8.1, see
[Troubleshooting](#9-troubleshooting).

### Step 1.5 — Start the database

```bash
brew services start mysql
```

You should see "Successfully started mysql".

### Step 1.6 — Create an empty database for the project

```bash
mysql -u root -e "CREATE DATABASE maruti_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Nothing visible happens if it works — that's good.

### Step 1.7 — Download the project

Pick a folder where you'd like to keep the project (your home folder is fine):

```bash
cd ~
git clone <your-project-url> travel
cd travel/portal
```

> Replace `<your-project-url>` with the actual link you were given (it should
> look like `https://github.com/.../travel.git`). If you already have the
> project on your computer, just run `cd /path/to/travel/portal` instead.

### Step 1.8 — Install the project's add-ons

```bash
composer install
```

```bash
npm install
```

Each of these can take 2–5 minutes. You'll see lots of text scrolling — that's
normal.

### Step 1.9 — Create the settings file

```bash
cp .env.example .env
php artisan key:generate
```

### Step 1.10 — Create the tables and demo data

```bash
php artisan migrate
php artisan db:seed
```

You'll see a list of green "DONE" messages. This sets up the database and
creates two demo accounts you can use to log in.

### Step 1.11 — Start the website

Keep this Terminal window open and run:

```bash
php artisan serve
```

You should see a message like
`Server running on [http://127.0.0.1:8000]`. **Leave this window open** —
closing it stops the website.

### Step 1.12 — Start the design builder (in a new window)

Open a **second** Terminal window (**Cmd + N** while in Terminal), then:

```bash
cd ~/travel/portal
npm run dev
```

Leave this window open too.

### Step 1.13 — Open the website

Open your browser and go to:

<http://127.0.0.1:8000>

Log in with one of these demo accounts:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@maruti.test` | `password` |
| Agent | `agent@maruti.test` | `password` |

You're done! Jump to [Section 3 — Using it day-to-day](#3-using-it-day-to-day).

---

## 2. Windows setup

### Step 2.1 — Open PowerShell as Administrator

Click the **Start** button, type `PowerShell`, **right-click** "Windows
PowerShell", and choose **Run as administrator**. Click "Yes" when Windows asks.

A dark blue window will open. This is where you'll paste commands.

> **Tip:** to paste into PowerShell, right-click in the window. To run a
> command, paste it and press **Enter**. Wait for the blinking cursor to
> return before running the next one.

### Step 2.2 — Install Chocolatey

Chocolatey is a free tool that installs other tools for you. Paste this in and
press Enter:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

This takes 1–2 minutes. When it finishes, **close PowerShell and reopen it as
administrator** (Step 2.1) so the new tool is available.

Check it worked:

```powershell
choco --version
```

You should see a version number like `2.x.x`.

### Step 2.3 — Install everything the project needs

Paste this one command:

```powershell
choco install -y php --version=8.2.999 ; choco install -y composer ; choco install -y nodejs-lts ; choco install -y mysql ; choco install -y git
```

This installs:
- **PHP 8.2** — the language the website is written in.
- **Composer** — installs PHP add-ons.
- **MySQL** — where the website stores its data.
- **Node.js** — needed to build the website's design (CSS / JavaScript).
- **Git** — used to download the project code.

This takes 10–20 minutes depending on your internet speed. You'll see lots of
text. As long as it ends with no red "ERROR" lines, you're good.

When it finishes, **close PowerShell and reopen it as administrator** so the
new tools are available.

Verify:

```powershell
php -v
composer -V
node -v
git --version
```

Each should print a version number. If any say "not recognized", see
[Troubleshooting](#9-troubleshooting).

### Step 2.4 — Turn on the PHP features the website needs

Find your PHP settings file:

```powershell
php --ini
```

Look for the line that says `Loaded Configuration File:` — it's the path to a
file called `php.ini`. Open that file in Notepad (right-click → Open with →
Notepad).

In the file, use **Ctrl + F** to find each of these lines, and **remove the
semicolon (`;`) at the start** of each one:

```
;extension=bcmath
;extension=curl
;extension=fileinfo
;extension=gd
;extension=mbstring
;extension=openssl
;extension=pdo_mysql
;extension=zip
```

So `;extension=curl` becomes `extension=curl`, and so on.

Save the file (Ctrl + S) and close Notepad.

### Step 2.5 — Create an empty database for the project

```powershell
mysql -u root -e "CREATE DATABASE maruti_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Nothing visible happens if it works — that's good. If it asks for a password,
press Enter (the default is no password).

### Step 2.6 — Download the project

Pick a folder for the project. Your `Documents` folder is fine:

```powershell
cd $HOME\Documents
git clone <your-project-url> travel
cd travel\portal
```

> Replace `<your-project-url>` with the actual link you were given (it should
> look like `https://github.com/.../travel.git`). If you already have the
> project on your computer, just run `cd C:\path\to\travel\portal` instead.

### Step 2.7 — Install the project's add-ons

```powershell
composer install
```

```powershell
npm install
```

Each of these can take 2–5 minutes. Lots of scrolling text is normal.

### Step 2.8 — Create the settings file

```powershell
copy .env.example .env
php artisan key:generate
```

### Step 2.9 — Create the tables and demo data

```powershell
php artisan migrate
php artisan db:seed
```

You'll see a list of green "DONE" messages. This sets up the database and
creates two demo accounts you can use to log in.

### Step 2.10 — Start the website

Keep this PowerShell window open and run:

```powershell
php artisan serve
```

You should see `Server running on [http://127.0.0.1:8000]`. **Leave this
window open** — closing it stops the website.

### Step 2.11 — Start the design builder (in a new window)

Open a **second** PowerShell window (Start → PowerShell, no need for admin this
time), then:

```powershell
cd $HOME\Documents\travel\portal
npm run dev
```

Leave this window open too.

### Step 2.12 — Open the website

Open your browser and go to:

<http://127.0.0.1:8000>

Log in with one of these demo accounts:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@maruti.test` | `password` |
| Agent | `agent@maruti.test` | `password` |

You're done! Continue to the next section.

---

## 3. Using it day-to-day

### Starting the website again (after restarting your computer)

You don't need to repeat the setup. Just open **two** Terminal / PowerShell
windows and run:

**Window 1 — Mac:**

```bash
cd ~/travel/portal
php artisan serve
```

**Window 1 — Windows:**

```powershell
cd $HOME\Documents\travel\portal
php artisan serve
```

**Window 2 — both:**

```bash
npm run dev
```

(Use the same `cd` command first.)

Then open <http://127.0.0.1:8000> in your browser.

### Stopping the website

In each of the two windows, press **Ctrl + C**. Both windows can then be closed.

### Getting the latest changes

If someone updates the project and you want their changes:

```bash
git pull
composer install
npm install
php artisan migrate
```

Then start the website as usual.

---

## 4. Demo login accounts

These are created automatically by Step 1.10 / Step 2.9. They only exist on
your computer — they're not real accounts on the live website.

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@maruti.test` | `password` |
| Agent | `agent@maruti.test` | `password` |

If you want to reset everything to a clean state (this deletes any data you
created locally), run:

```bash
php artisan migrate:fresh --seed
```

---

## 5. Where things live

You don't need to know this to use the website, but in case you're curious:

```
travel/
├── README.md       ← this file
├── PRD.md          ← what the product does (Product Requirements)
├── HLD.md          ← high-level design
├── LLD.md          ← low-level design
└── portal/         ← the actual website code
    └── .env        ← settings file (database, mail, etc.)
```

---

## 9. Troubleshooting

If a step didn't work, find your error below.

### "command not found: brew" (Mac)

Homebrew didn't finish installing or didn't add itself to your shell. Run this
and try again:

```bash
echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zshrc
source ~/.zshrc
```

On an Intel Mac, replace `/opt/homebrew` with `/usr/local`.

### "php is not recognized" (Windows)

You probably need to restart PowerShell. Close every PowerShell window and
open a fresh one. If it still doesn't work, restart your computer once.

### `php -v` shows the wrong version (Mac)

You skipped Step 1.4. Run those two commands again, then close and reopen
Terminal.

### "SQLSTATE[HY000] [2002]" or "Can't connect to MySQL"

The database isn't running.

- **Mac:** `brew services start mysql`
- **Windows:** open PowerShell as Administrator and run `Start-Service MySQL80`
  (or `Get-Service MySQL*` to see the exact name).

### "Access denied for user 'root'@'localhost'"

MySQL is asking for a password. If you've never set one, the default is empty
— just press Enter when it asks. If you did set one, open `portal/.env` in a
text editor and put your password after `DB_PASSWORD=` (with no quotes).

### "Port 8000 is already in use"

Another program is using that port. Start the website on a different port:

```bash
php artisan serve --port=8001
```

Then open <http://127.0.0.1:8001> instead.

### `npm install` fails with weird errors

Delete the folder it created and try again:

**Mac:**

```bash
rm -rf node_modules package-lock.json
npm install
```

**Windows:**

```powershell
Remove-Item -Recurse -Force node_modules, package-lock.json
npm install
```

### The website loads but the design looks broken (no colors, no layout)

You forgot to start the design builder. Open a second window and run
`npm run dev` from the `portal` folder (Step 1.12 / Step 2.11).

### I want to start over completely

Inside the `portal` folder:

```bash
php artisan migrate:fresh --seed
```

This wipes the database and recreates the demo accounts. Your code stays
untouched.

### Something else broke

Take a screenshot of the error and send it to whoever shared this project with
you. Include the last command you ran.
