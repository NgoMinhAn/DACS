# Laragon Pretty URL Setup Instructions

This guide will help you set up a pretty URL (`tourguide.dacn`) for your project in Laragon instead of using `localhost:8081`.

## Step 1: Copy Virtual Host Configuration

1. Open Laragon
2. Click **Menu** → **Tools** → **Open Apache httpd.conf**
3. Or navigate to: `C:\laragon\etc\apache2\sites-enabled\`
4. Create a new file named `tourguide.dacn.conf` (or copy the provided `laragon-tourguide.dacn.conf` file)
5. Copy the contents from `laragon-tourguide.dacn.conf` and paste it into the new file
6. **Important**: Update the `DocumentRoot` and `Directory` paths in the config file to match your actual project path if it's different from `C:/Users/MP/Desktop/DA`

## Step 2: Update Hosts File

1. Open Notepad as Administrator (Right-click → Run as administrator)
2. Open the hosts file located at: `C:\Windows\System32\drivers\etc\hosts`
3. Add this line at the end:
   ```
   127.0.0.1    tourguide.dacn
   ```
4. Save the file

## Step 3: Restart Laragon

1. In Laragon, click **Stop All**
2. Wait a few seconds
3. Click **Start All**

## Step 4: Verify Setup

1. Open your browser
2. Navigate to: `http://tourguide.dacn`
3. Your project should now load with the pretty URL!

## Troubleshooting

### If `tourguide.dacn` doesn't work:

1. **Check Apache is running**: Make sure Apache is started in Laragon
2. **Check hosts file**: Ensure the entry `127.0.0.1 tourguide.dacn` exists in your hosts file
3. **Check virtual host config**: Verify the paths in `tourguide.dacn.conf` match your project location
4. **Clear browser cache**: Sometimes browsers cache DNS, try clearing cache or using incognito mode
5. **Check Laragon logs**: Go to Laragon → Apache → Error Log to see any errors

### Alternative: Use a different domain name

If you prefer a different domain name (like `tourguide.test`):
1. Update `ServerName` in the virtual host config
2. Update the entry in hosts file
3. Update `URL_ROOT` in `app/config/config.php`

## Notes

- The `.htaccess` file is already configured correctly for pretty URLs
- The `URL_ROOT` in `config.php` has been updated to `http://tourguide.dacn`
- No port number is needed with virtual hosts
- Laragon's auto-virtual host feature might also work, but manual setup gives more control

