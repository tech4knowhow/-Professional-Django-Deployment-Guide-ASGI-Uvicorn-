# 🚀 Professional Django Deployment Guide (ASGI + Uvicorn)
This repository provides a comprehensive workflow for deploying a Python Django Web Application on a Windows production environment using **ASGI** and **Uvicorn**. It includes configurations for localization, security hardening, and automatic background services using **NSSM**.

## ⚙️ Professional Environment Setup
To maintain a professional workflow, we avoid manual `.env` changes by splitting settings files:

* `PROJECTNAME/settings/development.py` (Local testing)
* `PROJECTNAME/settings/production.py` (Production server)

## 🛠 Step 1: Locate & Prepare the App
Ensure your application code is placed in its production directory (e.g., `C:\inetpub\wwwroot\appname` or `C:\Users\Administrator\appname`).

## 🌍 Step 2: Internationalization (gettext)
Configure Django to handle translations correctly on Windows.

### 1. Install gettext Binaries
* Download **EZWinPorts gettext**: [SourceForge Link](https://sourceforge.net/projects/ezwinports/files/)
* Extract to `C:\gettext\`
* Add `C:\gettext\bin` to your **System Environment Variables (PATH)**.

### 2. Verify Installation
```powershell
msgfmt --version
```

### 3. Localization Workflow
```powershell
# Create/update .po files
django-admin makemessages -l am
# Compile .po to .mo
python manage.py compilemessages -i venv
```

### 🧠 The "Rule of 5" for Translations
1. **Language code:** Must match the folder name.
2. **Correct plural rule:** (e.g., Amharic: `nplurals=1; plural=0;`).
3. **One header per language:** Never copy headers between languages.
4. **Clean Compiling:** Delete old `.mo` files before recompiling.
5. **Python 3.12+:** Strict enforcement of translation syntax.

## 📦 Step 3: Environment & Dependencies
This guide assumes **Python 3.13+** and **Uvicorn**.

### 1. Setup Virtual Environment
```powershell
python -m venv venv
.\venv\Scripts\Activate.ps1
pip install -r requirements.txt
```

### 2. Troubleshooting (Recreate Environment)
If your environment breaks or Uvicorn fails:
```powershell
deactivate
Remove-Item -Recurse -Force .\venv
python -m venv venv
```

## 🛡️ Step 4: Security Hardening Checklist
 
### 🔒 Firewall & SSL Setup
Install Chocolatey and `mkcert` for local SSL certificates:
```powershell
# Install mkcert
choco install mkcert
mkcert -install
mkcert 127.0.0.1 localhost
# Configure Windows Firewall (Run as Admin)
New-NetFirewallRule -DisplayName "DJANGO-HTTP" -Direction Inbound -LocalPort 80 -Protocol TCP -Action Allow
New-NetFirewallRule -DisplayName "DJANGO-HTTPS" -Direction Inbound -LocalPort 443 -Protocol TCP -Action Allow
# Block External Database Access
New-NetFirewallRule -DisplayName "Block-External-DB" -Direction Inbound -LocalPort 1433,6379 -Protocol TCP -Action Block
```

## ⚙️ Step 5: Automation with NSSM (Recommended)
Use **NSSM** (Non-Sucking Service Manager) to run your Django app as a background service that starts automatically with Windows.
```powershell
# Install NSSM
choco install nssm
# Install the service
nssm install PROJECTNAME
# Management commands
nssm start PROJECTNAME
nssm restart PROJECTNAME
nssm edit PROJECTNAME
```

## ✅ Summary Checklist
1. [ ] **Locate:** App moved to production folder.
2. [ ] **Localization:** Gettext configured and messages compiled.
3. [ ] **Venv:** Dependencies installed and verified.
4. [ ] **Security:** SSL applied and Firewall rules set.
5. [ ] **Deploy:** NSSM service created and running.

## 📄 License

This guide and associated scripts are provided "as-is" for the community. Use responsibly.
 
