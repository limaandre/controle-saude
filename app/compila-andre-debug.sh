sudo rm -rf /Users/andre/Documents/www/apks/debug-app-minha-saude.apk
sudo rm -rf /Users/andre/Documents/www/minha-saude/app/platforms/android/app/build/outputs/apk/debug/app-debug.apk
sudo cordova build android --debug
sudo cp /Users/andre/Documents/www/minha-saude/app/platforms/android/app/build/outputs/apk/debug/app-debug.apk /Users/andre/Documents/www/apks/debug-app-minha-saude1.apk