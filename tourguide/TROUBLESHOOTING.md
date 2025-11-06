# Troubleshooting tourguide.dacn Setup

## Quick Checklist

Run through these steps to diagnose why `tourguide.dacn` isn't working:

### 1. Verify Virtual Host File Location ✅
- Your virtual host file `tourguide.dacn.conf` should be in: `C:\laragon\etc\apache2\sites-enabled\`
- Make sure it's copied there (not just in your project folder)

### 2. Verify Hosts File Entry ✅
Open PowerShell as Administrator and run:
```powershell
Get-Content C:\Windows\System32\drivers\etc\hosts | Select-String "tourguide.dacn"
```

You should see: `127.0.0.1    tourguide.dacn`

If not, add it manually:
1. Open Notepad as Administrator
2. File → Open → `C:\Windows\System32\drivers\etc\hosts`
3. Add line: `127.0.0.1    tourguide.dacn`
4. Save

### 3. Verify Apache Configuration ✅
Laragon should auto-include virtual hosts, but verify:
- Open: `C:\laragon\etc\apache2\httpd.conf`
- Look for: `IncludeOptional sites-enabled/*.conf`
- If missing, add it near the end of the file

### 4. Verify Virtual Host File Format ✅
Your `tourguide.dacn.conf` should look exactly like this:

```apache
<VirtualHost *:80>
    ServerName tourguide.dacn
    ServerAlias *.tourguide.dacn
    DocumentRoot "C:/Users/MP/Desktop/DA"
    
    <Directory "C:/Users/MP/Desktop/DA">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "C:/Users/MP/Desktop/DA/logs/error.log"
    CustomLog "C:/Users/MP/Desktop/DA/logs/access.log" common
</VirtualHost>
```

**Important:** Use forward slashes `/` in paths, not backslashes `\`

### 5. Restart Everything ✅
1. In Laragon: **Stop All** → Wait 5 seconds → **Start All**
2. Close and reopen your browser (or use Incognito/Private mode)
3. Clear browser DNS cache:
   - Chrome: `chrome://net-internals/#dns` → Clear host cache
   - Or use: `ipconfig /flushdns` in PowerShell as Administrator

### 6. Test Apache Configuration ✅
In PowerShell, navigate to Laragon's Apache bin folder and test:
```powershell
cd C:\laragon\bin\apache\apache2.4.*\bin
.\httpd.exe -t
```

Then restart Apache configuration:
```powershell
.\httpd.exe -k restart
```

### 7. Check Apache Error Logs ✅
Check Laragon → Apache → Error Log, or manually:
- `C:\laragon\etc\apache2\logs\error.log`
- Look for any errors related to `tourguide.dacn`

### 8. Test DNS Resolution ✅
In PowerShell, test if DNS resolves:
```powershell
ping tourguide.dacn
```

Should show: `Pinging tourguide.dacn [127.0.0.1]`

### 9. Alternative: Use Laragon's Auto Virtual Host ✅
Laragon has an auto-virtual host feature:
1. In Laragon, right-click your project folder
2. Select "Add Virtual Host" or similar
3. It should auto-create the virtual host

### 10. Fallback: Try .test Domain ✅
If `.dacn` still doesn't work, try `.test`:
- Change config to: `http://tourguide.test`
- Update virtual host `ServerName` to: `tourguide.test`
- Update hosts file to: `127.0.0.1    tourguide.test`

## Common Issues & Solutions

### Issue: "Site can't be reached" or "ERR_CONNECTION_REFUSED"
- **Solution:** Apache isn't running or virtual host isn't loaded
- Check Laragon shows Apache as "Running"
- Verify virtual host file is in `sites-enabled` folder

### Issue: "This site can't be reached" or DNS error
- **Solution:** Hosts file entry missing or incorrect
- Verify hosts file has `127.0.0.1    tourguide.dacn`
- Run `ipconfig /flushdns` as Administrator

### Issue: "403 Forbidden" or "Directory Listing Denied"
- **Solution:** Directory permissions issue
- Verify `<Directory>` in virtual host config matches your project path exactly
- Check `AllowOverride All` is set

### Issue: Page loads but shows wrong content or 404
- **Solution:** `.htaccess` rewrite rules issue
- Verify `.htaccess` file exists in project root
- Check Apache `mod_rewrite` is enabled (usually is in Laragon)

### Issue: Virtual host works but URLs are broken
- **Solution:** `URL_ROOT` in `config.php` needs to match
- Verify `URL_ROOT` is `http://tourguide.dacn` (no trailing slash)

## Still Not Working?

1. Check if `localhost:8081` still works (to verify Apache is running)
2. Try accessing `http://127.0.0.1` directly
3. Check Laragon's Apache configuration includes virtual hosts
4. Verify your project folder path is correct (no spaces or special characters causing issues)

