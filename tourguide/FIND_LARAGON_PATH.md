# Finding Your Laragon Apache Configuration

## Quick Method

1. **Open Laragon**
2. Click **Menu** → **Tools** → **Open Apache httpd.conf**
   - This will open the file location directly
   - Note the path shown in the address bar

## Common Laragon Installation Paths

Laragon can be installed in various locations:
- `C:\laragon\`
- `D:\laragon\`
- `C:\Laragon\` (capital L)
- Or any custom location you chose during installation

## Finding Your Installation Path

### Method 1: Check Laragon's Menu
1. Open Laragon
2. Look at the top menu bar
3. The installation path is usually shown in the window title or menu

### Method 2: Search Your Computer
1. Open File Explorer
2. Search for `httpd.conf` in your C: drive
3. Look for results in paths like: `C:\...\laragon\etc\apache2\httpd.conf`

### Method 3: Check Laragon Settings
1. In Laragon, click **Menu** → **Preferences** or **Settings**
2. Look for installation path or Apache path

## What You Need to Find

Once you find your Laragon path, you need these locations:

1. **Apache Configuration Directory:**
   ```
   [YOUR_LARAGON_PATH]\etc\apache2\sites-enabled\
   ```
   Example: `C:\laragon\etc\apache2\sites-enabled\`

2. **Apache httpd.conf File:**
   ```
   [YOUR_LARAGON_PATH]\etc\apache2\httpd.conf
   ```
   Example: `C:\laragon\etc\apache2\httpd.conf`

## Updating Your Virtual Host File

Once you know your Laragon path:
1. Copy `tourguide.dacn.conf` to: `[YOUR_LARAGON_PATH]\etc\apache2\sites-enabled\`
2. Make sure the `DocumentRoot` path in the config file matches your project location

## Using Laragon's Built-in Tools

Laragon has convenient menu options:
- **Menu** → **Tools** → **Open Apache sites-enabled** - Opens the virtual hosts folder
- **Menu** → **Tools** → **Open Apache httpd.conf** - Opens the Apache config file

Use these to quickly navigate to the right locations!

