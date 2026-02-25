
✅✅DEPLOYMENT GUIDE FOR A PYTHON DJANGO WEB APPLICATION (ASGI + Uvicorn)
🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹
👉To maintain a professional workflow and avoid manual changes to your .env
 values when switching between your local machine and a production server, 
 it is best practice to split your settings files:
⚙️ Professional Environment Setup
  😉PROJECTNAME/settings/development.py
  😉PROJECTNAME/settings/production.py 
🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹

STEP 1✅.GET AND LOCATE THE APP
STEP 2✅.LOACALIZATION ALSO CONSIDER FOR GETTEXT AND CONFIGURE IT:
 //INTERNATIONALIZATION Configuration
      pip install --upgrade Pygments
*install package - download libintl-0.14.4-bin for win os
*adding **gettext** to your Windows PATH so Django can use it for translations.
  🔧 Step 1: Install gettext:
  Download the **gettext binaries for Windows**: - [EZWinPorts gettext (recommended)]
   (https://sourceforge.net/projects/ezwinports/files/)  
   → Look for a file like `gettext-0.19.8.1-w32-bin.zip`.
   → Or [GnuWin32 gettext]
   (https://gnuwin32.sourceforge.net/packages/gettext.htm).
  Extract or install it somewhere easy to find, e.g.: C:\gettext\
  Inside, you should see a `bin` folder with tools like `msgfmt.exe`, `xgettext.exe`.
  🔧 Step 2: Add gettext to PATH
     1. Press **Win + R**, type: sysdm.cpl and hit Enter.
	 2. Go to **Advanced → Environment Variables**.
	 3. Under **System variables**, find `Path` and click **Edit**.
	 4. Click **New** and add the path to the gettext `bin` folder, e.g.: C:\gettext\bin
	 5. Click **OK** to save.
  🔧 Step 3: Verify Installation
     1. Open a new **Command Prompt** (important — restart it so PATH updates).
	 2. Run:
	 where msgfmt
	 msgfmt --version	 or
	 "C:\gettext\bin\msgfmt.exe" --version
 If it prints a version number, gettext is installed correctly.
   🔧 Step 4: Use with Django
       Now you can run: first rewrite the and set model.py foreach for .po to be created
	   //Create/update .po files:
	   django-admin makemessages -l am
	   //Compile .po to .mo (this uses msgfmt):
	   django-admin compilemessages
	   //Django will use gettext to generate and compile your `.po` and `.mo` files.
	   C:\Users\Administrator\appnameORprojectname\locale\am\LC_MESSAGES
	   python manage.py compilemessages 
	   #FOR any other app:
	   e.g, cd C:\Users\Administrator\dusrms\core // core should the app next to projectname
	   mkdir locale
	   mkdir locale\am
	   mkdir locale\am\LC_MESSAGES
✅ 1️⃣ Validate **all** locale files (safe & fast)
    Step A — Find all `.po` files
	From your project root:
	//dir -Recurse -Filter django.po
	You should see paths like:
	locale\am\LC_MESSAGES\django.po
	locale\en\LC_MESSAGES\django.po
	analyticals\locale\...
	core\locale\...
    Step B — Check `Language` vs `Plural-Forms`
	Use this **golden table** (correct rules):
	| Language | Code | Plural-Forms|
	| -------- | ---- | ---------------------------------------------- |
	| Amharic  | `am` | `nplurals=1; plural=0;|
	| English  | `en` | `nplurals=2; plural=(n != 1);`|
	| French   | `fr` | `nplurals=2; plural=(n > 1);` |
	| Arabic   | `ar` | `nplurals=6; plural=(n==0 ? 0 : n==1 ? 1 : n==2 ? 2 :
	n%100>=3 && n%100<=10 ? 3 : n%100>=11 && n%100<=99 ? 4 : 5);` |
👉 **Rule:** `Language:` **must match** the correct plural rule.
    Step C — Delete all compiled `.mo` files
	This avoids stale corruption:
	-run use powershell
	del locale\am\LC_MESSAGES\django.mo ---- for project_name/
	del /s /q locale\*\LC_MESSAGES\*.mo 
	(Do the same inside app-level `locale/` folders if you have them.)
	Step D — Recompile cleanly
	python manage.py compilemessages -i venv
✅ If this passes, your translations are 100% valid.
🛡️ 2️⃣ Prevent this problem forever (best practices)
✅ Rule 1: Never guess plural rules
Always use Django’s generated header.
//python manage.py makemessages -l am
Django writes the **correct `Plural-Forms` automatically**.
✅ Rule 2: Don’t copy headers between languages
Only copy **msgid/msgstr**, never headers.
✅ Good:
Each language keeps its own header.
## 🧠 “Rule of 5” memory trick 😉
1. **Language code**
2. **Correct plural rule**
3. **One header per language**
4. **Delete `.mo` before compiling**
5. **Python 3.12 is strict**

🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹

STEP 3✅. INSTALL DEPENDENCY, PACKAGE TO THE VENV/
   → Install **Python 3.13+**
   → Install any code editor (e.g., **Visual Studio Code**)
   
## 2. Make sure you have the Django application you want to deploy
## 3. Understand your server choice: **WSGI vs ASGI**
   * WSGI → Traditional synchronous Django apps
   * ASGI → Supports WebSockets, async tasks, real-time features
This guide uses **ASGI** + **Uvicorn** for deployment:
🔗 [https://docs.djangoproject.com/en/5.2/howto/deployment/]

👉 Before install For SQL Server, make sure you have the 
👉 ODBC Driver 18 for SQL Server installed on your system

🔹 create the virtual environment
python -m venv venv
🔹 Activate the virtual environment
.\venv\Scripts\Activate.ps1
🔹Install dependencies
→If the project includes a `requirements.txt` file:
pip install -r requirements.txt
→If not, you must manually reinstall each required package.

👉IF NOT work: **Recreate and Fix Virtual Environment Issues**
*Recommended if your Uvicorn command fails or your environment is broken.*
Works on: **PowerShell Version 5.1.14393.5066**
### Check PowerShell Version
$PSVersionTable
###Deactivate (Optional)
→If Uvicorn or Python commands stop working:
deactivate
###Delete the existing virtual environment (Optional)
Remove-Item -Recurse -Force .\venv
🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹

STEP 4✅. DJANGO SECURITY HARDENING CHECKLIST

   🔧 Configuration
- `DEBUG = False` in `settings.py`  
- `ALLOWED_HOSTS` set to your domain/IP  
- Strong, unique `SECRET_KEY` (never checked into version control)  
- Use environment variables for sensitive settings (DB credentials, API keys)  
   🔒 HTTPS & Cookies
- `SECURE_SSL_REDIRECT = True` (force HTTPS)  
- `SESSION_COOKIE_SECURE = True`  
- `CSRF_COOKIE_SECURE = True`  
- `SESSION_COOKIE_HTTPONLY = True`  
- `SECURE_BROWSER_XSS_FILTER = True`  
- `SECURE_CONTENT_TYPE_NOSNIFF = True`  

  🛡️ Authentication & Authorization
- Use Django’s built‑in auth system (PBKDF2/Argon2 password hashing)  
- Enforce strong password policies  
- Apply role‑based permissions (Groups, `@permission_required`)  
- Limit superuser/admin accounts to trusted staff  

  📂 File & Data Handling
- Validate uploaded files (type, size)  
- Store uploads outside public static paths  
- Restrict direct access to media files  
- Encrypt sensitive data at rest (DB, backups)  

  🌐 Deployment
- Run behind **Gunicorn/Uvicorn + Nginx/Apache**  
- Use **systemd/Docker** for process isolation  
- Firewall allows only ports 80/443  
- Apply OS‑level hardening (disable root login, SSH key auth)  

  📊 Monitoring & Logging
- Enable Django logging for errors & suspicious activity  
- Monitor Nginx/Apache logs  
- Apply rate limiting for login attempts  
- Set up intrusion detection or monitoring tools  

  🔄 Maintenance
- Regularly update Django & dependencies  
- Apply OS security patches  
- Backup database & media securely  
- Test disaster recovery procedures

✅SOME SECURITY CONFIG DONE 👉
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
#for security - SSL
choco install mkcert
mkcert -install
#(C:\Users\Administrator\dusrms):
mkcert 127.0.0.1 localhost
🔹 Firewall: Allow 80/443 ONLY
# You need to create rules that allow the web traffic but block the "back doors" to your database and cache from the outside world.
# Run these commands once as Administrator to configure the Windows Firewall:
🔹 Allow Port 80 (for Django's internal redirect to HTTPS)
 New-NetFirewallRule -DisplayName "PROJECTNAME-HTTP" -Direction Inbound -LocalPort 80 -Protocol TCP -Action Allow
🔹 Allow Port 443 (The main Uvicorn port)
 New-NetFirewallRule -DisplayName "PROJECTNAME-HTTPS" -Direction Inbound -LocalPort 443 -Protocol TCP -Action Allow
🔹 Block Port 1433 (MSSQL) and 6379 (Redis) from External IPs
# This ensures only your server can talk to its own database.
 New-NetFirewallRule -DisplayName "Block-External-DB" -Direction Inbound -LocalPort 1433,6379 -Protocol TCP -Action Block
👉 How to Test Your Config:
→ Generate a New Secure Key: You can use Python’s built-in secrets module to generate a production-grade key. Run this command in your terminal:
 python -c 'import secrets; print(secrets.token_urlsafe(50))'
→ Before you go live, run Django’s built-in deployment check in your terminal:
  python manage.py check --deploy
# Verification Command: To see exactly what the world sees on your server, run:
Get-NetFirewallRule | Where-Object { $_.Enabled -eq 'True' -and $_.Direction -eq 'Inbound' } | Select-Object DisplayName, Action
🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹

STEP 5✅. AFTER DEPLOY TO AUTO RUN APP USE: TASK SCHEDULER OR NSSM / RECOMMENDED/
→Open PowerShell as Administrator and run this to install Chocolatey:
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
→Install NSSM:
choco install nssm
//Run the command from my previous message:
nssm install PROJECTNAME
→Open PowerShell as Administrator:
nssm start PROJECTNAME
nssm edit PROJECTNAME
nssm restart PROJECTNAME
👉To aut run the app on browser nssm is prefered as the .ps1 code should configured throurgh 
Task Scheduler and it is not better consistent 
🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹

STEP 6✅. NOTE: CONFIURE THE APP/PROJECT FOR LOCAL NETWORK AND WWW/PUBLIC AS NEED

🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹 🔹

SUMMMARY ✅.TO BE CONSIDERED DURING DEPLOY DJANGO WEB APP
→ Get and locate the app, 
→ Loacalization also consider for gettext and configure it
→ Install dependency/package to the venv
→ Consider security
→ Run the app use providedcode.ps1 start manually, providedcode.ps1 with Task Scheduler configured, nssm(recommended)
→ Confiure the app/project for local network and www/public as need

