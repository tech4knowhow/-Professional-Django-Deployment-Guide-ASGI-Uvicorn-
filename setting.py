"""
Django settings for PROJECTNAME project.
"""
from pathlib import Path
import os
from django.utils.translation import gettext_lazy as _
from dotenv import load_dotenv

# ------------------------------
# BASE
# ------------------------------
BASE_DIR = Path(__file__).resolve().parent.parent
load_dotenv(BASE_DIR / ".env") 
# ------------------------------
# SECURITY & AUTHENTICATION
# ------------------------------
SECRET_KEY = os.getenv("DJANGO_SECRET_KEY")
DEBUG = os.getenv("DJANGO_DEBUG", "False").lower() == "true"

# Fix: Ensure ALLOWED_HOSTS is a clean list
ALLOWED_HOSTS = [h.strip() for h in os.getenv("DJANGO_ALLOWED_HOSTS", "").split(",") if h.strip()]

# CSRF handling
raw_origins = os.getenv("CSRF_TRUSTED_ORIGINS", "")
CSRF_TRUSTED_ORIGINS = [origin.strip() for origin in raw_origins.split(",") if origin.strip()]

def get_bool(name, default="False"):
    return os.getenv(name, default).lower() == "true"

# IMPORTANT: In local development, if SECURE_SSL_REDIRECT is True but you aren't 
# using HTTPS, you will get an "Infinite Redirect" loop.
SECURE_SSL_REDIRECT = get_bool("SECURE_SSL_REDIRECT")
SESSION_COOKIE_SECURE = get_bool("SESSION_COOKIE_SECURE")
CSRF_COOKIE_SECURE = get_bool("CSRF_COOKIE_SECURE")

# HSTS Settings (Only active if SECURE_SSL_REDIRECT is True)
if SECURE_SSL_REDIRECT:
    SECURE_HSTS_SECONDS = int(os.getenv("SECURE_HSTS_SECONDS", 31536000))
    SECURE_HSTS_INCLUDE_SUBDOMAINS = True
    SECURE_HSTS_PRELOAD = True

X_FRAME_OPTIONS = "DENY"
SECURE_CONTENT_TYPE_NOSNIFF = True


# ------------------------------
# INSTALLED APPS replace
# ------------------------------
INSTALLED_APPS = [
    'core.apps.CoreConfig',
    'feedbcks.apps.FeedbacksConfig',
    'analytics.apps.AnalyticsConfig',
    'dconfigs.apps.DefaultconfigsConfig',
    'PROJECTNAME.auth_app.MyAuthConfig',

    'django.contrib.admin',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',

    'rest_framework',
    'smart_selects',

    'allauth',
    'allauth.account',
]
SITE_ID = 1

ACCOUNT_EMAIL_VERIFICATION = "none"
ACCOUNT_LOGIN_METHODS = ['username', 'email']
ACCOUNT_FORMS = {
    "signup": "core.forms.CustomSignupForm",
}

AUTHENTICATION_BACKENDS = [
    'django.contrib.auth.backends.ModelBackend',
    'allauth.account.auth_backends.AuthenticationBackend',
]

AUTH_USER_MODEL = 'core.User' #core i.e appname

LOGIN_REDIRECT_URL = '/PROJECTNAME/'

# ------------------------------
# REST FRAMEWORK
# ------------------------------
REST_FRAMEWORK = {
    'DEFAULT_AUTHENTICATION_CLASSES': (
        'rest_framework.authentication.SessionAuthentication',
        'rest_framework.authentication.TokenAuthentication',
    ),
    'DEFAULT_PERMISSION_CLASSES': (
        'rest_framework.permissions.IsAuthenticated',
    ),
}

# ------------------------------
# MIDDLEWARE
# ------------------------------
MIDDLEWARE = [
    'django.middleware.security.SecurityMiddleware',
    'whitenoise.middleware.WhiteNoiseMiddleware',

    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.locale.LocaleMiddleware',
    'core.middleware.ForceAdminAmharicMiddleware',

    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',

    'allauth.account.middleware.AccountMiddleware',
]

# ------------------------------
# LOCALES replace
# ------------------------------
LOCALE_PATHS = (
    BASE_DIR / 'locale',
    BASE_DIR / 'analytics' / 'locale',
    BASE_DIR / 'core' / 'locale',
    BASE_DIR / 'dconfigs' / 'locale',
    BASE_DIR / 'feedbcks' / 'locale',
)

ROOT_URLCONF = 'PROJECTNAME.urls'

# ------------------------------
# TEMPLATES
# ------------------------------
TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [BASE_DIR / 'templates'],
        #'DIRS': [],
        'APP_DIRS': True,
        'OPTIONS': {
            'context_processors': [
                'django.template.context_processors.request',
                'django.contrib.auth.context_processors.auth',
                'django.contrib.messages.context_processors.messages',
            ],
        },
    },
]

# ------------------------------
# ASGI / CHANNELS
# ------------------------------
ASGI_APPLICATION = 'PROJECTNAME.asgi.application'

CHANNEL_LAYERS = {
    'default': {
        'BACKEND': 'channels_redis.core.RedisChannelLayer',
        'CONFIG': {
            'hosts': [(
                os.getenv("REDIS_HOST"),
                int(os.getenv("REDIS_PORT"))
            )],
        },
    },
}

# ------------------------------
# DATABASE (SQL SERVER)
# ------------------------------
DATABASES = {
    'default': {
        'ENGINE': os.getenv("DB_ENGINE"),
        'NAME': os.getenv("DB_NAME"),
        'HOST': os.getenv("DB_HOST"),
        'PORT': os.getenv("DB_PORT"),
        'USER': os.getenv("DB_USER"),
        'PASSWORD': os.getenv("DB_PASSWORD"),
        'OPTIONS': {
            'driver': os.getenv("DB_DRIVER"),
            'Encrypt': 'no',
            'extra_params': 'TrustServerCertificate=yes',
        },
    }
}

# ------------------------------
# PASSWORD POLICIES (Your Specific Request)
# ------------------------------
PASSWORD_HASHERS = [
    'django.contrib.auth.hashers.Argon2PasswordHasher', # Priority 1
    'django.contrib.auth.hashers.PBKDF2PasswordHasher',
    'django.contrib.auth.hashers.PBKDF2SHA1PasswordHasher',
]

AUTH_PASSWORD_VALIDATORS = [
    {'NAME': 'django.contrib.auth.password_validation.UserAttributeSimilarityValidator'},
    {
        'NAME': 'django.contrib.auth.password_validation.MinimumLengthValidator',
        'OPTIONS': {'min_length': 12} 
    },
    {'NAME': 'django.contrib.auth.password_validation.CommonPasswordValidator'},
    {'NAME': 'django.contrib.auth.password_validation.NumericPasswordValidator'},
]

# ------------------------------
# ✅ DJANGO LOGGING CONFIG
# ------------------------------
LOGGING = {
    'version': 1,
    'disable_existing_loggers': False,

    'handlers': {
        'file': {
            'level': 'ERROR',
            'class': 'logging.FileHandler',
            'filename': BASE_DIR / 'logs/django-error.log',
        },
        'auth': {
            'level': 'INFO',
            'class': 'logging.FileHandler',
            'filename': BASE_DIR / 'logs/auth.log',
        },
    },

    'loggers': {
        'django': {
            'handlers': ['file'],
            'level': 'ERROR',
            'propagate': True,
        },
        'django.security': {
            'handlers': ['file'],
            'level': 'ERROR',
            'propagate': False,
        },
        'django.contrib.auth': {
            'handlers': ['auth'],
            'level': 'INFO',
            'propagate': False,
        },
    },
}
INSTALLED_APPS += ['simple_history']
MIDDLEWARE += ['simple_history.middleware.HistoryRequestMiddleware']

# ------------------------------
# INTERNATIONALIZATION replace as needed
# ------------------------------
TIME_ZONE = 'Africa/Addis_Ababa'
USE_TZ = True

LANGUAGE_CODE = 'am'
# LANGUAGE_CODE = 'am-et'
LANGUAGES = [
    ('am', _('Amharic')),
    ('en', _('English')),
]

USE_I18N = True
USE_L10N = True
LANGUAGE_COOKIE_NAME = 'django_language'

# ------------------------------
# STATIC & MEDIA
# ------------------------------
STATIC_URL = '/static/'
STATIC_ROOT = BASE_DIR / "staticfiles"
STATICFILES_DIRS = [BASE_DIR / "static"]
STATICFILES_STORAGE = 'whitenoise.storage.CompressedManifestStaticFilesStorage'

MEDIA_URL = '/media/'
MEDIA_ROOT = BASE_DIR / "media"

# ------------------------------
# DEFAULT PRIMARY KEY
# ------------------------------
DEFAULT_AUTO_FIELD = 'django.db.models.BigAutoField'
