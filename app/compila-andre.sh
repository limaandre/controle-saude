sudo rm -rf /Users/andre/Documents/www/apks/app-minha-saude.apk
sudo rm -rf /Users/andre/Documents/www/minha-saude/app/platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk
sudo ionic cordova build android --minifycss --optimizejs --minifyjs --release --prod
sudo jarsigner -storepass andre123 -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore keyEver.keystore -tsa http://timestamp.digicert.com /Users/andre/Documents/www/minha-saude/app/platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk  alias_name
sudo /Users/andre/Library/Android/sdk/build-tools/28.0.3/zipalign -v 4 /Users/andre/Documents/www/minha-saude/app/platforms/android/app/build/outputs/apk/release/app-release-unsigned.apk /Users/andre/Documents/www/apks/app-minha-saude.apk
